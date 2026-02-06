import { useState, useEffect } from "react"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@catalyst/table"
import { useTranslation } from 'react-i18next'
import fetchApi from "../fetchApi";


interface MitarbeiterData {
    users: Mitarbeiter[]
}

interface Mitarbeiter {
    id: number
    vorname: string
    nachname: string
}



export default function mitarbeiter() {
    const [data, setData] = useState<MitarbeiterData | null>(null)
     const { t, i18n } = useTranslation()

    const [_, setLanguage] = useState(() => {
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
            fetchApi('/mitarbeiter')
                .then((jsonData: MitarbeiterData) => {
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
                        <h1 className="text-2xl font-semibold">{t("employees")}</h1>
                    </div>
                    <Table>
                        <TableHead>
                            <TableRow>
                                <TableHeader>ID</TableHeader>
                                <TableHeader>{t("firstName")}</TableHeader>
                                <TableHeader>{t("lastName")}</TableHeader>
                            </TableRow>
                        </TableHead>
                    <TableBody>
                        {data.users.map((mitarbeiter) => {
                            return <TableRow key={mitarbeiter.id}>
                                <TableCell>{mitarbeiter.id}</TableCell>
                                <TableCell>{mitarbeiter.vorname}</TableCell>
                                <TableCell>{mitarbeiter.nachname}</TableCell>
                                
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





                            