import { useState, useEffect } from "react"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@catalyst/table"
import { useTranslation } from 'react-i18next'
import fetchApi from "../fetchApi";


interface Arbeitseintrag {
    start: string
    ende: string
    tag: string
}

interface Arbeitstag {
    arbeitszeiten: Arbeitseintrag[]
}

interface ArbeitsMonat {
    tage: { [tag: string]: Arbeitstag }
}

interface ArbeitsJahr {
    monate: { [monat: string]: ArbeitsMonat }
}

interface ArbeitsJahrCollection {
    jahre: { [jahr: string]: ArbeitsJahr }
}

interface UeberstundenData {
    arbeitszeiten: ArbeitsJahrCollection
    ueberstundenProMonat: { [key: string]: number }
    ueberstundenProJahr: { [key: string]: number }
    wochen: { [key: string]: number }
}

export default function Ueberstunden() {
    const [data, setData] = useState<UeberstundenData | null>(null)
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

  const changeLanguage = (lang: string) => {
    i18n.changeLanguage(lang)
    localStorage.setItem('language', lang)
    setLanguage(lang)
  }

    useEffect(() => {
            fetchApi('/Ueberstunden')
                .then((jsonData: UeberstundenData) => {
                    console.log('Dashboard data:', jsonData);
                    setData(jsonData);
                })
                .catch((error: unknown) => {
                    console.error('Fetch error:', error);
                });
        }, []);

    const berechneStunden = (arbeitszeiten: Arbeitseintrag[]): number => {
        let gesamtStunden = 0
        arbeitszeiten.forEach(eintrag => {
            const start = new Date(eintrag.start)
            const ende = new Date(eintrag.ende)
            const diff = (ende.getTime() - start.getTime()) / (1000 * 60 * 60)
            gesamtStunden += diff
        })
        return Math.round(gesamtStunden * 100) / 100
    }

    const berechneUeberstunden = (arbeitszeiten: Arbeitseintrag[]): number => {
        const gesamtStunden = berechneStunden(arbeitszeiten)
        return Math.round((gesamtStunden - 8) * 100) / 100
    }

    return (
        <main>
            {data && data.arbeitszeiten ?
                <div>
                    {Object.entries(data.arbeitszeiten.jahre).map(([jahr, jahrDaten]) => (
                        <div key={jahr}>
                            <h2 className="text-2xl font-bold mt-6 mb-4 text-white">{t('year')} {jahr}</h2>
                            {Object.entries(jahrDaten.monate).map(([monat, monatDaten]) => (
                                <div key={`${jahr}-${monat}`} className="mb-8">
                                    <h3 className="text-xl font-semibold mb-3 text-white">{t('month')} {monat}</h3>
                                    <Table>
                                        <TableHead>
                                            <TableRow>
                                                <TableHeader>{t('date')}</TableHeader>
                                                <TableHeader>{t('start')}</TableHeader>
                                                <TableHeader>{t('end')}</TableHeader>
                                                <TableHeader>{t('workingHours')}</TableHeader>
                                                <TableHeader>{t('overtime')}</TableHeader>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>
                                            {Object.entries(monatDaten.tage).map(([tag, tagDaten]) => {
                                                const stunden = berechneStunden(tagDaten.arbeitszeiten)
                                                const ueberstunden = berechneUeberstunden(tagDaten.arbeitszeiten)
                                                return tagDaten.arbeitszeiten.map((eintrag, idx) => (
                                                    <TableRow key={`${tag}-${idx}`}>
                                                        {idx === 0 && (
                                                            <>
                                                                <TableCell rowSpan={tagDaten.arbeitszeiten.length}>
                                                                    {new Date(eintrag.tag).toLocaleDateString()}
                                                                </TableCell>
                                                            </>
                                                        )}
                                                        <TableCell>{new Date(eintrag.start).toLocaleTimeString()}</TableCell>
                                                        <TableCell>{new Date(eintrag.ende).toLocaleTimeString()}</TableCell>
                                                        {idx === 0 && (
                                                            <>
                                                                <TableCell rowSpan={tagDaten.arbeitszeiten.length}>{stunden}h</TableCell>
                                                                <TableCell rowSpan={tagDaten.arbeitszeiten.length}>
                                                                    <span className={stunden > 10 ? "bg-zinc-900 text-red-600 font-semibold" 
                                                                                                  : ueberstunden >= 0 
                                                                                                    ? "bg-zinc-900 text-green-600 font-semibold" 
                                                                                                    : "text-orange-600 font-semibold"
                                                                                    }>
                                                                        {ueberstunden > 0 ? '+' : ''}{ueberstunden}h
                                                                    </span>
                                                                </TableCell>
                                                            </>
                                                        )}
                                                    </TableRow>
                                                ))
                                            })}
                                        </TableBody>
                                    </Table>
                                </div>
                            ))}
                        </div>
                    ))}
                </div>
                : <div>{t('loading')}</div>}
        </main>
    )
}