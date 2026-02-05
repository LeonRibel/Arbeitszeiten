import { useState, useEffect, useRef } from "react";
import { useTranslation } from 'react-i18next';
import fetchApi from "../fetchApi";

interface Kunde {
    id: number;
    firmenname: string;
}

export default function NeuArbeitszeit() {
    const { t } = useTranslation();
    const [aufgaben, setAufgaben] = useState<string>('');
    const [kunde, setKunde] = useState<string>('');
    const [kunden, setKunden] = useState<Kunde[]>([]);

    const [timerLäuft, setTimerLäuft] = useState<boolean>(false);
    const [timerId, setTimerId] = useState<number | null>(null);
    const [timerStart, setTimerStart] = useState<number | null>(null);
    const [zeit, setZeit] = useState<number>(0);
    const intervalRef = useRef<ReturnType<typeof setInterval> | null>(null);

    useEffect(() => {
        fetchApi('/kunden')
            .then(response => {
                const data = response.data || response || [];
                setKunden(Array.isArray(data) ? data : []);
            })
            .catch(() => {});
    }, []);

    useEffect(() => {
        fetchApi('/arbeitszeiten/timer/running')
            .then(response => {
                if (response && response.data) {
                    const startMs = new Date(response.data.start).getTime();
                    setTimerId(response.data.id);
                    setTimerStart(startMs);
                    setZeit(Math.floor((Date.now() - startMs) / 1000));
                    setTimerLäuft(true);
                    if (response.data.aufgaben) setAufgaben(response.data.aufgaben);
                    if (response.data.kunde_id) setKunde(String(response.data.kunde_id));
                }
            })
            .catch(() => {});
    }, []);

    useEffect(() => {
        if (timerLäuft && timerStart) {
            intervalRef.current = setInterval(() => {
                setZeit(Math.floor((Date.now() - timerStart) / 1000));
            }, 1000);
        }
        return () => {
            if (intervalRef.current) clearInterval(intervalRef.current);
        };
    }, [timerLäuft, timerStart]);

    const formatZeit = (sekunden: number): string => {
        const h = Math.floor(sekunden / 3600);
        const m = Math.floor((sekunden % 3600) / 60);
        const s = sekunden % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    };

    const handleTimerStart = async () => {
        try {
            const response = await fetchApi('/arbeitszeiten/timer/start', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    kunde_id: kunde ? parseInt(kunde) : null,
                    aufgaben: aufgaben
                })
            });
            const startMs = new Date(response.data.start).getTime();
            setTimerId(response.data.id);
            setTimerStart(startMs);
            setZeit(0);
            setTimerLäuft(true);
        } catch (err) {
            console.error('Timer Start Fehler:', err);
            alert('Timer konnte nicht gestartet werden.');
        }
    };

    const handleTimerStop = async () => {
        if (!timerId) return;
        try {
            await fetchApi(`/arbeitszeiten/timer/${timerId}/stop`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ aufgaben: aufgaben })
            });
            setTimerLäuft(false);
            setTimerId(null);
            setTimerStart(null);
            setZeit(0);
            setAufgaben('');
            setKunde('');
            window.location.href = '/';
        } catch (err) {
            console.error('Timer Stop Fehler:', err);
            alert('Timer konnte nicht gestoppt werden.');
        }
    };

    return (
        <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
                    {t('newWorkTime')}
                </h2>
            </div>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm space-y-6">
                {/* Timer */}
                <div className="rounded-lg bg-white/5 p-6 text-center">
                    <div className="text-5xl font-mono font-bold text-white mb-6">
                        {formatZeit(zeit)}
                    </div>
                    {!timerLäuft ? (
                        <button
                            type="button"
                            onClick={handleTimerStart}
                            className="rounded-md bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-500"
                        >
                            Timer starten
                        </button>
                    ) : (
                        <button
                            type="button"
                            onClick={handleTimerStop}
                            className="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white hover:bg-red-500"
                        >
                            Timer stoppen
                        </button>
                    )}
                </div>

                {/* Aufgaben */}
                <div>
                    <label htmlFor="aufgaben" className="block text-sm/6 font-medium text-gray-100">
                        {t('tasks')}
                    </label>
                    <div className="mt-2">
                        <textarea
                            id="aufgaben"
                            name="aufgaben"
                            value={aufgaben}
                            onChange={(e) => setAufgaben(e.target.value)}
                            rows={4}
                            className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        />
                    </div>
                </div>

                {/* Kunde */}
                <div>
                    <label htmlFor="kunde" className="block text-sm/6 font-medium text-gray-100">
                        {t('customer')}
                    </label>
                    <div className="mt-2">
                        <select
                            id="kunde"
                            name="kunde"
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

                {/* Zurück */}
                <p className="mt-4 text-center text-sm/6 text-gray-400">
                    <a href="/" className="font-semibold text-indigo-400 hover:text-indigo-300">
                        {t('backToDashboard')}
                    </a>
                </p>
            </div>
        </div>
    );
}
