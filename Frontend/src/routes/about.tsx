import { useTranslation } from 'react-i18next'

export default function About() {
    const { t } = useTranslation()
    return (
        <main>
            <h1>{t('about')}</h1>
        </main>
    )
}