
import { useState, useEffect, useRef } from "react"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@catalyst/table"
import { Link } from "react-router-dom"
import { CalendarDaysIcon, ClockIcon, PlusCircleIcon } from '@heroicons/react/24/outline'
import { useTranslation } from 'react-i18next'
import fetchApi from "../fetchApi";



interface DashboardData {
    kw: number;
    tage: {
        [tag: string]: Array<{
            ist_pause: boolean;
            id?: number;
            start?: string;
            ende?: string;
            aufgaben?: string;
            dauer: string;
        }>
    };
    gesamtzeit: string;
    kunde?: { id: number; firmenname: string } | null;
}
export default function Index() {
    const { t } = useTranslation()
    const [data, setData] = useState<DashboardData | null>(null)

    const [timerLäuft, setTimerLäuft] = useState<boolean>(false);
    const [timerId, setTimerId] = useState<number | null>(null);
    const [timerStart, setTimerStart] = useState<number | null>(null);
    const [zeit, setZeit] = useState<number>(0);
    const intervalRef = useRef<ReturnType<typeof setInterval> | null>(null);

    useEffect(() => {
        fetchApi('/arbeitszeiten')
            .then((jsonData: DashboardData) => {
                setData(jsonData);
            })
            .catch((error: unknown) => {
                console.error('Fetch error:', error);
            });
    }, []);

    // Beim Laden prüfen ob ein Timer läuft
    useEffect(() => {
        fetchApi('/arbeitszeiten/timer/running')
            .then(response => {
                if (response && response.data) {
                    const startMs = new Date(response.data.start).getTime();
                    setTimerId(response.data.id);
                    setTimerStart(startMs);
                    setZeit(Math.floor((Date.now() - startMs) / 1000));
                    setTimerLäuft(true);
                }
            })
            .catch(() => {});
    }, []);

    // Tick wenn Timer läuft
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

    const handleTimerStop = async () => {
        if (!timerId) return;
        try {
            await fetchApi(`/arbeitszeiten/timer/${timerId}/stop`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({})
            });
            setTimerLäuft(false);
            setTimerId(null);
            setTimerStart(null);
            setZeit(0);
            // Dashboard-Daten neu laden damit der Eintrag aktuell ist
            fetchApi('/arbeitszeiten').then((jsonData: DashboardData) => setData(jsonData)).catch(() => {});
        } catch (err) {
            console.error('Timer Stop Fehler:', err);
            alert('Timer konnte nicht gestoppt werden.');
        }
    };

    return (
        <main>
            {data ? (
                <>
                    <div className="flex justify-between items-center mb-6">
                        <div>
                            <h1 className="text-2xl font-semibold">{t('workTimes')}</h1>
                            <div className="flex gap-4 mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <span className="inline-flex items-center gap-1">
                                    <CalendarDaysIcon className="w-4 h-4" />
                                    {new Date().toLocaleDateString('de-DE', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                                </span>
                                <span className="inline-flex items-center gap-1">
                                    <ClockIcon className="w-4 h-4" />
                                    KW {data.kw}
                                </span>
                            </div>
                        </div>
                        <Link
                            to="/neu"
                            className="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                        >
                            <PlusCircleIcon className="w-5 h-5" />
                            {t('newWorkTime')}
                        </Link>
                    </div>
                    {/* Laufender Timer Banner */}
                    {timerLäuft && (
                        <div className="flex items-center justify-between mb-4 rounded-lg bg-green-900/40 border border-green-700 px-4 py-3">
                            <div className="flex items-center gap-3">
                                <span className="inline-block w-3 h-3 rounded-full bg-green-400 animate-pulse"></span>
                                <span className="text-green-300 font-medium">Timer läuft</span>
                                <span className="text-green-100 font-mono font-bold text-lg">{formatZeit(zeit)}</span>
                            </div>
                            <button
                                type="button"
                                onClick={handleTimerStop}
                                className="rounded-md bg-red-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-red-500"
                            >
                                Stoppen
                            </button>
                        </div>
                    )}

                    <Table>
                        <TableHead>
                            <TableRow>
                                <TableHeader>{t('day')}</TableHeader>
                                <TableHeader>{t('tasks')}</TableHeader>
                                <TableHeader>{t('start')}</TableHeader>
                                <TableHeader>{t('end')}</TableHeader>
                                <TableHeader>{t('total')}</TableHeader>
                                <TableHeader>{t('edit')}</TableHeader>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {data.tage && Object.entries(data.tage).flatMap(([tag, eintraege]) => {
                                return eintraege.map((eintrag, index) => {
                                    if (eintrag.ist_pause) {
                                        return (
                                            <TableRow key={`${tag}-pause-${index}`} className="bg-zinc-100 dark:bg-zinc-800">
                                                <TableCell></TableCell>
                                                <TableCell colSpan={3} className="text-center italic text-stone-500 dark:text-stone-400">
                                                    {t('pause')}: {eintrag.dauer}
                                                </TableCell>
                                                <TableCell></TableCell>
                                                <TableCell></TableCell>
                                            </TableRow>
                                        )
                                    }

                                    return (
                                        <TableRow key={`${tag}-${index}`}>
                                            <TableCell>{index === 0 ? tag : ''}</TableCell>
                                            <TableCell>{eintrag.aufgaben}</TableCell>
                                            <TableCell className="italic text-blue-500 dark:text-blue-400">{eintrag.start ? new Date(eintrag.start).toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit' }) : '-'} </TableCell>
                                            <TableCell className="italic text-red-500 dark:text-red-400">{eintrag.ende ? new Date(eintrag.ende).toLocaleTimeString('de-DE', { hour: '2-digit', minute: '2-digit' }) : '-'}</TableCell>
                                            <TableCell>{eintrag.dauer}</TableCell>
                                            <TableCell>
                                                <Link to={`/arbeitszeiten/${eintrag.id}`} className="button-links">
                                                    <svg className=" hover:stroke-blue-300 hover:stroke-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" width="20" height="20">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M16.862 3.487a2.25 2.25 0 1 1 3.182 3.182L7.125 19.586l-4.607 1.425 1.425-4.607L16.862 3.487z" />
                                                    </svg>
                                                </Link>
                                            </TableCell>
                                        </TableRow>
                                    )
                                })
                            })}
                        </TableBody>
                    </Table>
                </>
            ) : (
                <div>{t('loading')}</div>
            )}
        </main>
    )
}
