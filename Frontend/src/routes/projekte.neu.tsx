import { useState, useEffect } from "react";
import { useTranslation } from 'react-i18next';
import fetchApi from "../fetchApi";

interface Kunde {
  id: number;
  firmenname: string;
}

export default function NeuProjekt() {
  const { t } = useTranslation();
  const [kunde, setKunde] = useState<string>(''); // enthält jetzt die kunden-id als string
  const [aufgabe, setAufgabe] = useState<string>('');
  const [gesamt, setGesamt] = useState<string>('');
  const [kunden, setKunden] = useState<Kunde[]>([]);

  // Lade Kunden aus der Datenbank
  useEffect(() => {
    fetchApi('/kunden')
      .then((data: Kunde[]) => {
        setKunden(data);
      })
      .catch((error) => {
        console.error('Fehler beim Laden der Kunden:', error);
      });
  }, []);


  const handleSubmit = async (e: React.FormEvent) => {
  e.preventDefault();

  const projektData = {
    kunde_id: parseInt(kunde), // Als Zahl, nicht String
    aufgabe: aufgabe,
    gesamt: parseFloat(gesamt), // Als Zahl, nicht String
    status: 'aktiv'
  };

  console.log('Sende neue Daten:', projektData);

  try {
    const response = await fetchApi('/projekte', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(projektData) // JSON statt FormData
    });

    console.log('Antwort vom Server:', response);
    window.location.href = '/projekte';
  } catch (err) {
    console.error('Fehler beim Erstellen des Projekts:', err);
    alert(t('networkError') + ': ' + err);
  }
};

  return (
    <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
      <div className="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
          {t('new project')}
        </h2>
      </div>

      <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form method="post" onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label htmlFor="kunde" className="block text-sm/6 font-medium text-gray-100">
              {t('customer')}
            </label>
            <div className="mt-2">
              <select
                id="kunde"
                name="kunde"
                required
                value={kunde}
                onChange={(e) => setKunde(e.target.value)}
                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
              >
                <option value="">Kunde auswählen...</option>
                {kunden.map((k) => (
                  <option key={k.id} value={String(k.id)}>
                    {k.firmenname}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <div>
            <label htmlFor="aufgabe" className="block text-sm/6 font-medium text-gray-100">
              {t('tasks')}
            </label>
            <div className="mt-2">
              <input
                id="aufgabe"
                type="text"
                name="aufgabe"
                required
                value={aufgabe}
                onChange={(e) => setAufgabe(e.target.value)}
                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>

          <div>
            <label htmlFor="gesamt" className="block text-sm/6 font-medium text-gray-100">
              {t('gesamt')}
            </label>
            <div className="mt-2">
              <input
                id="gesamt"
                type="number"
                name="gesamt"
                required
                value={gesamt}
                onChange={(e) => setGesamt(e.target.value)}
                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              className="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
            >
              {t('create')}
            </button>
          </div>
        </form>

        <p className="mt-10 text-center text-sm/6 text-gray-400">
          <a href="/projekte" className="font-semibold text-indigo-400 hover:text-indigo-300">
            {t('backToDashboard')}
          </a>
        </p>
      </div>
    </div>
  );
}