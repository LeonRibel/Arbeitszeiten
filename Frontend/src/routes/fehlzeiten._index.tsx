
import { useState, useEffect } from "react"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@catalyst/table"
import { Link } from "react-router-dom"
import { useTranslation } from 'react-i18next'
import fetchApi from "../fetchApi";


interface Fehlzeit {
    fehlzeiten_id: number
    mitarbeiter_id: number
    Vorname: string
    Nachname: string
    Kstart: string
    Kende: string
    tage: number
    status: string
}

interface FehlzeitenData {
    fehlzeiten: Fehlzeit[]

}

export default function Fehlzeiten() {
    const { t } = useTranslation()
    const [data, setData] = useState<FehlzeitenData | null>(null)

    useEffect(() => {
        fetchApi('/fehlzeiten')
            .then((jsonData: FehlzeitenData) => {
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
                        <h1 className="text-2xl font-semibold">{t('fehlzeiten')}</h1>
                        <Link
                            to="/Fehlzeiten/neu"
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
                            {t('newAbsence')}
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
                                <TableHeader>{t('upload')}</TableHeader>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {data.fehlzeiten.map((fehlzeit) => {
                                return <TableRow key={fehlzeit.fehlzeiten_id}>
                                    <TableCell>{new Date(fehlzeit.Kstart).toLocaleDateString()}</TableCell>
                                    <TableCell>{new Date(fehlzeit.Kende).toLocaleDateString()}</TableCell>
                                    <TableCell>{fehlzeit.tage}</TableCell>
                                    <TableCell className={fehlzeit.status === 'eingereicht' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}>
                                        {fehlzeit.status}
                                    </TableCell>
                                    <TableCell >
                                        <Link to={`/Fehlzeiten/${fehlzeit.fehlzeiten_id}`} className="button-links">
                                            <svg className=" hover:stroke-blue-300 hover:stroke-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="2" stroke="currentColor" width="20" height="20">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M16.862 3.487a2.25 2.25 0 1 1 3.182 3.182L7.125 19.586l-4.607 1.425 1.425-4.607L16.862 3.487z" />
                                            </svg>

                                        </Link>
                                    </TableCell>
                                    <TableCell><a href={`/fehlzeiten/${fehlzeit.fehlzeiten_id}/upload`} className="button-links">
                                        <svg className=" hover:stroke-blue-300 hover:stroke-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v4h16v-4M12 12v8M8 8l4-4 4 4" />
                                        </svg>
                                    </a></TableCell>
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
