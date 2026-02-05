import { useState } from "react";
import { useTranslation } from 'react-i18next';
import fetchApi from "../fetchApi";

export default function NeuFehlzeiten() {
    const { t } = useTranslation();
    const [Kstart, setKstart] = useState<string>('');
    const [Kende, setKende] = useState<string>('');


    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('krankheit_start', Kstart);
        formData.append('krankheit_ende', Kende);


        console.log('Sende neue Daten:', {
            Kstart,
            Kende,
        });

        try {
            await fetchApi('/fehlzeiten', {
                method: 'POST',
                body: formData
            });

            window.location.href = '/fehlzeiten';
        } catch (err) {
            console.error('Fehler beim Erstellen der Fehlzeiten:', err);
            alert(t('networkError') + ': ' + err);
        }
    };

    return (
        <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
                    {t('newAbsence')}
                </h2>
            </div>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form method="post" onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label htmlFor="Krankheit_start" className="block text-sm/6 font-medium text-gray-100">
                            {t('absenceStart')}
                        </label>
                        <div className="mt-2">
                            <input
                                id="Krankheit_start"
                                type="date"
                                name="Krankheit_start"
                                required
                                value={Kstart}
                                onChange={(e) => setKstart(e.target.value)}
                                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <div>
                        <label htmlFor="Krankheit_ende" className="block text-sm/6 font-medium text-gray-100">
                            {t('absenceEnd')}
                        </label>
                        <div className="mt-2">
                            <input
                                id="Krankheit_ende"
                                type="date"
                                name="Krankheit_ende"
                                required
                                value={Kende}
                                onChange={(e) => setKende(e.target.value)}
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
                    <a href="/Mitarbeiter" className="font-semibold text-indigo-400 hover:text-indigo-300">
                        {t('backToDashboard')}
                    </a>
                </p>
            </div>
        </div>
    );
}