import { useState, useEffect, Fragment } from "react"
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow
} from "@catalyst/table"
import { Link } from "react-router-dom"
import { useTranslation } from 'react-i18next'
import fetchApi from "../fetchApi"

type Kundenart = 'B2B' | 'B2C'

interface KundenData {
    kunden: Kunden[]
}

interface Kunden {
    id: number
    firmenname: string
    ansprechpartner: string
    email: string
    ort: string
    straße: string
    land: string
    plz: string
    hausnummer: string
    ust_id: string
    handelsregister_id: string
    telefon: string
    kundenart: Kundenart
}

export default function Mitarbeiter() {
    const [data, setData] = useState<KundenData | null>(null)
    const [expandedId, setExpandedId] = useState<number | null>(null)
    const { t, i18n } = useTranslation()

    const [language, setLanguage] = useState(() => {
        return localStorage.getItem('language') || 'de'
    })

    useEffect(() => {
        const saved = localStorage.getItem('language')
        if (saved && i18n.language !== saved) {
            i18n.changeLanguage(saved)
            setLanguage(saved)
        }
    }, [i18n])

    useEffect(() => {
        fetchApi('/kunden')
            .then((jsonData: Kunden[]) => {
                setData({ kunden: jsonData })
            })
            .catch((error: unknown) => {
                console.error('Fetch error:', error)
            })
    }, [])

    const toggleRow = (id: number) => {
        setExpandedId(prev => (prev === id ? null : id))
    }

    return (
        <main>
            {data ? (
                <>
                    <div className="flex justify-between items-center mb-6">
                        <h1 className="text-2xl font-semibold">
                            {t("Kunden")}
                        </h1>
                        <Link
                            to="/kunden/neu"
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
                            {t('new customer')}
                        </Link>
                    </div>

                    <Table>
                        <TableHead>
                            <TableRow>
                                <TableHeader>{t("Firmenname")}</TableHeader>
                            </TableRow>
                        </TableHead>

                        <TableBody>
                            {data.kunden.map((kunden) => (
                                <Fragment key={kunden.id}>
                                    <TableRow
                                        onClick={() => toggleRow(kunden.id)}
                                        className="cursor-pointer hover:bg-gray-100"
                                    >
                                        <TableCell className="font-medium flex justify-between items-center">
                                            {kunden.firmenname}
                                            <span className="text-gray-400">
                                                {expandedId === kunden.id ? "▼" : "▶"}
                                            </span>
                                        </TableCell>
                                    </TableRow>

                                    {expandedId === kunden.id && (
                                        <TableRow>
                                            <TableCell colSpan={1}>
                                                <div className="grid grid-cols-2 gap-2 text-sm p-2">
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("Ansprechpartner")}:</strong> {kunden.ansprechpartner}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("E-mail")}:</strong> {kunden.email}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("Telefon")}:</strong> {kunden.telefon}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("Ort")}:</strong> {kunden.ort}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("Straße")}:</strong> {kunden.straße} {kunden.hausnummer}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("PLZ")}:</strong> {kunden.plz}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("Land")}:</strong> {kunden.land}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("USt-ID")}:</strong> {kunden.ust_id}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("Handelsregister")}:</strong> {kunden.handelsregister_id}</div>
                                                    <div><strong className="text-blue-500 dark:text-blue-400">{t("Kundenart")}:</strong> {kunden.kundenart}</div>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </Fragment>
                            ))}
                        </TableBody>
                    </Table>
                </>
            ) : (
                <div>{t('loading')}</div>
            )}
        </main>
    )
}
