import { useState, useEffect } from "react"
import { useTranslation } from 'react-i18next'
import fetchApi from "../fetchApi";


interface ProfilData{
    profil:Profil[]
}

interface Profil{
    id: number;
    Vorname: string;
    Nachname: string;
    username: string;
    Urlaubstage: number;
    Resturlaub: number;
    Fehltage: number;
    Tage: number;
}


export default function Profil() {
    const [data, setData] = useState<ProfilData | null>(null)
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
            fetchApi('/MeinProfil')
                .then((jsonData: ProfilData) => {
                    console.log('Dashboard data:', jsonData);
                    setData(jsonData);
                })
                .catch((error: unknown) => {
                    console.error('Fetch error:', error);
                });
        }, []);
return (

        <div className=" bg-gray-50 dark:bg-zinc-900 py-24 sm:py-32 min-h-screen">
             {data ? (
                 <div className="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                    {data.profil.map((profil) => (
                        <div key={profil.id}>
                            <h2 className="text-center text-base/7 font-semibold text-indigo-600 dark:text-indigo-400">{t('myProfile')}</h2>
                            <p className="mx-auto mt-2 max-w-lg text-center text-4xl font-semibold tracking-tight text-balance text-gray-900 dark:text-white sm:text-5xl">
                                {profil.Vorname} {profil.Nachname}
                            </p>
                            <p className="text-center text-gray-600 dark:text-gray-400 mt-2">@{profil.username}</p>

                            <div className="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
                                {/* Urlaubstage Card */}
                                <div className="profile-card relative lg:row-span-2">
                                    <div className="absolute inset-px rounded-lg bg-white dark:bg-gray-800 lg:rounded-l-4xl" />
                                    <div className="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                                        <div className="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                                            <p className="mt-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white max-lg:text-center">{t('days')}</p>
                                            <p className="mt-2 max-w-lg text-sm/6 text-gray-600 dark:text-gray-400 max-lg:text-center">
                                                {t('yourVacationDays')}
                                            </p>
                                        </div>
                                        <div className="flex flex-1 items-center justify-center px-8 pb-8">
                                            <div className="text-center">
                                                <p className="text-8xl font-bold text-blue-600 dark:text-blue-400">{profil.Urlaubstage}</p>
                                                <p className="text-gray-600 dark:text-gray-400 mt-4">{t('available')}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-black/10 dark:outline-white/15 lg:rounded-l-4xl" />
                                </div>

                                {/* Genommene Tage Card */}
                                <div className=" profile-card relative max-lg:row-start-1">
                                    <div className="absolute inset-px rounded-lg bg-white dark:bg-gray-800 max-lg:rounded-t-4xl" />
                                    <div className="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
                                        <div className="px-8 pt-8 sm:px-10 sm:pt-10">
                                            <p className="mt-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white max-lg:text-center">{t('takenDays')}</p>
                                            <p className="mt-2 max-w-lg text-sm/6 text-gray-600 dark:text-gray-400 max-lg:text-center">
                                               {t('usedVacation')}
                                            </p>
                                        </div>
                                        <div className="flex flex-1 items-center justify-center px-8 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2">
                                            <div className="text-center">
                                                <p className="text-6xl font-bold text-orange-600 dark:text-orange-400">{profil.Tage}</p>
                                                <p className="text-gray-600 dark:text-gray-400 mt-2">{t('used')}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-black/10 dark:outline-white/15 max-lg:rounded-t-4xl" />
                                </div>

                                {/* Resturlaub Card */}
                                <div className=" profile-card relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                                    <div className="absolute inset-px rounded-lg bg-white dark:bg-gray-800" />
                                    <div className="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)]">
                                        <div className="px-8 pt-8 sm:px-10 sm:pt-10">
                                            <p className="mt-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white max-lg:text-center">{t('remaining')}</p>
                                            <p className="mt-2 max-w-lg text-sm/6 text-gray-600 dark:text-gray-400 max-lg:text-center">
                                                {t('remainingVacationDays')}
                                            </p>
                                        </div>
                                        <div className="flex flex-1 items-center justify-center max-lg:py-6 lg:pb-2">
                                            <div className="text-center">
                                                <p className="text-6xl font-bold text-green-600 dark:text-green-400">{profil.Resturlaub}</p>
                                                <p className="text-gray-600 dark:text-gray-400 mt-2">{t('daysRemaining')}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-black/10 dark:outline-white/15" />
                                </div>

                                {/* Fehltage Card */}
                                <div className=" profile-card relative lg:row-span-2">
                                    <div className="absolute inset-px rounded-lg bg-white dark:bg-gray-800 max-lg:rounded-b-4xl lg:rounded-r-4xl" />
                                    <div className="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
                                        <div className="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                                            <p className="mt-2 text-lg font-medium tracking-tight text-gray-900 dark:text-white max-lg:text-center">{t('absence')}</p>
                                            <p className="mt-2 max-w-lg text-sm/6 text-gray-600 dark:text-gray-400 max-lg:text-center">
                                               {t('yourAbsences')}
                                            </p>
                                        </div>
                                        <div className="flex flex-1 items-center justify-center px-8 pb-8">
                                            <div className="text-center">
                                                <p className="text-8xl font-bold text-red-600 dark:text-red-400">{profil.Fehltage}</p>
                                                <p className="text-gray-600 dark:text-gray-400 mt-4">{t('missing')}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="pointer-events-none absolute inset-px rounded-lg shadow-sm outline outline-black/10 dark:outline-white/15 max-lg:rounded-b-4xl lg:rounded-r-4xl" />
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="text-center text-gray-600 dark:text-gray-400">{t('loading')}</div>
            )}
        </div>
    )
}
