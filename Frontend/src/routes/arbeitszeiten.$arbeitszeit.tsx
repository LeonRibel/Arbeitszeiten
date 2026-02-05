import { useState, useEffect } from "react";
import { useTranslation } from 'react-i18next';
import fetchApi from '../fetchApi';

interface EditArbeitszeitProps {
    params: {
        arbeitszeit: string
    }
}

interface ArbeitszeitDaten {
    vorhandene_daten: {
        id: number;
        start: string;
        ende: string;
        aufgaben: string;
    }
}


export default function EditArbeitszeit({ params }: EditArbeitszeitProps) {
    const { t } = useTranslation();
    const [arbeitszeitDaten, setArbeitszeitDaten] = useState<ArbeitszeitDaten>({ vorhandene_daten: {} });
    const [start, setStart] = useState<string>('');
    const [ende, setEnde] = useState<string>('');
    const [aufgaben, setAufgaben] = useState<string>('');

    useEffect(() => {
        fetchApi(`/arbeitszeiten/${params.arbeitszeit}`)
            .then(response => {
                console.log('Geladene Daten:', response);
                const data = response.data || response;
                setArbeitszeitDaten({ vorhandene_daten: data });
                setStart(data.start || '');
                setEnde(data.ende || '');
                setAufgaben(data.aufgaben || '');
            })
            .catch(err => console.error('Fehler beim Laden der Arbeitszeitdaten:', err));
    }, [params.arbeitszeit]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        const payload = {
            start: start,
            ende: ende,
            aufgaben: aufgaben,
        };

        console.log('Sende Daten:', payload);

        try {
            await fetchApi(`/arbeitszeiten/${params.arbeitszeit}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            window.location.href = '/';
        } catch (err) {
            console.error('Fehler beim Aktualisieren der Arbeitszeit:', err);
        }
    };

    return (
    <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div className="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
                {t('editWorkTime')}
            </h2>
        </div>

        <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form method="post" onSubmit={handleSubmit} className="space-y-6">
                <div>
                    <label htmlFor="start_von" className="block text-sm/6 font-medium text-gray-100">
                        {t('start')}
                    </label>
                    <div className="mt-2">
                        <input
                            id="start_von"
                            type="datetime-local"
                            name="Start_von"
                            required
                            value={start}
                            onChange={(e) => setStart(e.target.value)}
                            className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        />
                    </div>
                </div>

                <div>
                    <label htmlFor="ende_bis" className="block text-sm/6 font-medium text-gray-100">
                        {t('end')}
                    </label>
                    <div className="mt-2">
                        <input
                            id="ende_bis"
                            type="datetime-local"
                            name="Ende_bis"
                            required
                            value={ende}
                            onChange={(e) => setEnde(e.target.value)}
                            className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        />
                    </div>
                </div>

                <div>
                    <label htmlFor="aufgaben" className="block text-sm/6 font-medium text-gray-100">
                        {t('tasks')}
                    </label>
                    <div className="mt-2">
                        <textarea
                            id="aufgaben"
                            name="Aufgaben"
                            required
                            value={aufgaben}
                            onChange={(e) => setAufgaben(e.target.value)}
                            rows={4}
                            className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        />
                    </div>
                </div>

                <input
                    type="hidden"
                    name="id"
                    value={arbeitszeitDaten.vorhandene_daten?.id ?? params.arbeitszeit ?? ''}
                />

                <div>
                    <button
                        type="submit"
                        className="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                    >
                        {t('confirm')}
                    </button>
                </div>
            </form>

            <p className="mt-10 text-center text-sm/6 text-gray-400">
                <a href="/" className="font-semibold text-indigo-400 hover:text-indigo-300">
                    {t('backToDashboard')}
                </a>
            </p>
        </div>
    </div>
);
}
