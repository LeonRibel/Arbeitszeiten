import { useState, useEffect } from "react"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@catalyst/table"
import { Link } from "react-router-dom"
import { useTranslation } from 'react-i18next'
import fetchApi from "../fetchApi";


interface UrlaubData {
    urlaube: {
        data: Urlaub[]
    }
    urlaubsanspruchGesamt: number
    urlaubsGeplant: number
}

interface Urlaub {

    id: number
    Mitarbeiter_id: number
    Vorname: string
    start: string
    ende: string
    status: string
    tage: number
}

export default function Urlaub() {
    const { t } = useTranslation()
    const [data, setData] = useState<UrlaubData | null>(null)

    useEffect(() => {
            fetchApi('/urlaub')
                .then((jsonData: UrlaubData) => {
                    console.log('Dashboard data:', jsonData);
                    setData(jsonData);
                })
                .catch((error: unknown) => {
                    console.error('Fetch error:', error);
                });
        }, []);
    
    return (
        <main>
            {data ? (
                <>
                    <div className="flex justify-between items-center mb-6">
                        <h1 className="text-2xl font-semibold">{t('vacationRequests')}</h1>
                        <Link
                            to="/Urlaub/neu"
                            className="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                strokeWidth="2"
                                stroke="currentColor"
                                width="20"
                                height="20"
                            >
                                <circle cx="12" cy="12" r="10" />
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M12 8v8M8 12h8"
                                />
                            </svg>
                            {t('newVacation')}
                        </Link>
                    </div>
                    <Table>
                        <TableHead>
                            <TableRow>
                                <TableHeader>{t('start')}</TableHeader>
                                <TableHeader>{t('end')}</TableHeader>
                                <TableHeader>{t('days')}</TableHeader>
                                <TableHeader>{t('status')}</TableHeader>
                                <TableHeader>{t('edit')}</TableHeader>
                            </TableRow>
                        </TableHead>
                    <TableBody>
                        {data.urlaube.data.map((urlaub: Urlaub) => {
                            return <TableRow key={urlaub.id}>
                                <TableCell>{new Date(urlaub.start).toLocaleDateString()}</TableCell>
                                <TableCell>{new Date(urlaub.ende).toLocaleDateString()}</TableCell>
                                <TableCell>{urlaub.tage}</TableCell>
                                <TableCell className={
                                    urlaub.status === 'angefragt' ? 'text-white dark:text-white' :
                                    urlaub.status === 'genehmigt' ? 'text-green-600 dark:text-green-400' :
                                    'text-red-600 dark:text-red-400'
                                }>
                                    {urlaub.status}
                                </TableCell>
                                <TableCell>
                                    <Link to={`/Urlaub/${urlaub.id}`} className="button-links">
                                        <svg className="hover:stroke-blue-300 hover:stroke-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" width="20" height="20">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M16.862 3.487a2.25 2.25 0 1 1 3.182 3.182L7.125 19.586l-4.607 1.425 1.425-4.607L16.862 3.487z" />
                                        </svg>
                                    </Link>
                                </TableCell>
                            </TableRow>
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
