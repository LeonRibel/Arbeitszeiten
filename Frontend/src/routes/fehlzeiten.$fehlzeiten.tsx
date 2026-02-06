import { useState, useEffect } from "react";
import { useTranslation } from 'react-i18next';
import fetchApi from "../fetchApi";

interface EditFehlzeitenProps {
  params: {
    fehlzeiten: number
  }
}

interface FehlzeitenDaten {
  fehlzeiten_id: number;
  Kstart: string;
  Kende: string;
  status: string;
  tage: number;
}

function formatDate(date?: string): string {
    if (!date) return "";

    const d = new Date(date);

    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
}

export default function EditFehlzeiten({ params }: EditFehlzeitenProps) {
  const { t } = useTranslation();
  const [_, setFehlzeitenDaten] = useState<FehlzeitenDaten | null>(null);
  const [startDatum, setStartDatum] = useState<string>('');
  const [endDatum, setEndDatum] = useState<string>('');

  useEffect(() => {
    fetchApi(`/fehlzeiten/${params.fehlzeiten}`)
      .then((data: FehlzeitenDaten) => {
        console.log('Geladene Fehlzeiten:', data);
        setFehlzeitenDaten(data);
        setStartDatum(formatDate(data.Kstart));
        setEndDatum(formatDate(data.Kende));
      })
      .catch(err => console.error('Fehler beim Laden der Fehlzeitendaten:', err));
  }, [params.fehlzeiten]);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('krankheit_start', startDatum);
    formData.append('krankheit_ende', endDatum);
    formData.append('_method', 'PUT'); // Laravel FormData PUT workaround

    console.log('Sende Daten:', {
      krankheit_start: startDatum,
      krankheit_ende: endDatum,
      id: params.fehlzeiten
    });

    try {
      await fetchApi(`/fehlzeiten/${params.fehlzeiten}`, {
        method: 'POST', // POST mit _method=PUT für FormData
        body: formData
      });

      window.location.href = '/fehlzeiten';
    } catch (err) {
      console.error('Fehler beim Aktualisieren der Fehlzeiten:', err);
      alert(t('networkError') + ': ' + err);
    }
};

  return (
    <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
      <div className="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
          {t('editAbsence')}
        </h2>
      </div>

      <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form method="post" onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="fehlzeiten_start" className="block text-sm/6 font-medium text-gray-100">
              {t('absenceStart')}
            </label>
            <div className="mt-2">
              <input
                id="fehlzeiten_start"
                type="date"
                name="fehlzeiten_start"
                required
                value={startDatum}
                onChange={(e) => setStartDatum(e.target.value)}
                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>

          <div>
            <label htmlFor="fehlzeiten_ende" className="block text-sm/6 font-medium text-gray-100">
              {t('absenceEnd')}
            </label>
            <div className="mt-2">
              <input
                id="fehlzeiten_ende"
                type="date"
                name="fehlzeiten_ende"
                required
                value={endDatum}
                onChange={(e) => setEndDatum(e.target.value)}
                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              className="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
            >
              {t('save')}
            </button>
          </div>
        </form>

        <p className="mt-10 text-center text-sm/6 text-gray-400">
          <a href="/fehlzeiten" className="font-semibold text-indigo-400 hover:text-indigo-300">
            {t('backToDashboard')}
          </a>
        </p>
      </div>
    </div>
  );
}
