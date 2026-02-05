import { useEffect } from "react"
import { useNavigate } from "react-router"
import { useTranslation } from "react-i18next"
import { Heading } from "@catalyst/heading"

export default function Logout() {
    const navigate = useNavigate()
    const { t } = useTranslation()

    useEffect(() => {
        const performLogout = async () => {
            try {
                await fetch('http://localhost/Logout', {
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                    }
                })

                navigate('/login')
            } catch (error) {
                console.error('Logout error:', error)
                navigate('/login')
            }
        }

        performLogout()
    }, [navigate])

    return (
        <div className="flex items-center justify-center min-h-screen">
            <div className="text-center">
                <Heading>{t('loggingOut')}</Heading>
                <p className="mt-4">{t('loggingOutMessage')}</p>
            </div>
        </div>
    )
}

