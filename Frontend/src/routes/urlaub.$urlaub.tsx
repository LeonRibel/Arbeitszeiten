import { useState, useEffect } from "react";
import { useTranslation } from 'react-i18next';
import fetchApi from "../fetchApi";

interface EditUrlaubProps {
  params: {
    urlaub: number
  }
}

interface UrlaubDaten {
  id: number;
  start: string;
  ende: string;
  status: string;
  tage: number;
}

function formatDate(date?: string): string {
    if (!date) return "";

    const d = new Date(date);

    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
}

export default function EditUrlaub({ params }: EditUrlaubProps) {
  const { t } = useTranslation();
  const [startDatum, setStartDatum] = useState<string>('');
  const [endDatum, setEndDatum] = useState<string>('');

  useEffect(() => {
    fetchApi(`/urlaub/${params.urlaub}`)
      .then((data: UrlaubDaten) => {
        console.log('Geladene Urlaubsdaten:', data);
        setStartDatum(formatDate(data.start));
        setEndDatum(formatDate(data.ende));
      })
      .catch(err => console.error('Fehler beim Laden der Urlaubsdaten:', err));
  }, [params.urlaub]);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('start', startDatum);
    formData.append('ende', endDatum);
    formData.append('_method', 'PUT'); // Laravel FormData PUT workaround

    console.log('Sende Daten:', {
      start: startDatum,
      ende: endDatum,
      id: params.urlaub
    });

    try {
      await fetchApi(`/urlaub/${params.urlaub}`, {
        method: 'POST', // POST mit _method=PUT für FormData
        body: formData
      });

      window.location.href = '/urlaub';
    } catch (err) {
      console.error('Fehler beim Aktualisieren des Urlaubs:', err);
      alert(t('networkError') + ': ' + err);
    }
  };

  return (
    <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
      <div className="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
          {t('editVacation')}
        </h2>
      </div>

      <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form method="post" onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="urlaub_start" className="block text-sm/6 font-medium text-gray-100">
              {t('vacationStart')}
            </label>
            <div className="mt-2">
              <input
                id="urlaub_start"
                type="date"
                name="urlaub_start"
                required
                value={startDatum}
                onChange={(e) => setStartDatum(e.target.value)}
                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>

          <div>
            <label htmlFor="urlaub_ende" className="block text-sm/6 font-medium text-gray-100">
              {t('vacationEnd')}
            </label>
            <div className="mt-2">
              <input
                id="urlaub_ende"
                type="date"
                name="urlaub_ende"
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
          <a href="/urlaub" className="font-semibold text-indigo-400 hover:text-indigo-300">
            {t('backToDashboard')}
          </a>
        </p>
      </div>
    </div>
  );
}
