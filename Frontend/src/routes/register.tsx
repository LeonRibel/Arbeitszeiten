import { useState, useEffect } from "react"
import { Heading } from "@catalyst/heading"
import { Field, Label } from "@catalyst/fieldset"
import { Input } from "@catalyst/input"
import { Button } from "@catalyst/button"
import { BACKEND_URL } from "../config"

export default function Register() {
    const [vorname, setVorname] = useState('')
    const [nachname, setNachname] = useState('')
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
    const [passwordConfirm, setPasswordConfirm] = useState('')
    const [error, setError] = useState('')
    const [isLoading, setIsLoading] = useState(false)

    // Prüft, ob der User bereits eingeloggt ist
    useEffect(() => {
        fetch(BACKEND_URL + '/user', {
            credentials: 'include',
            headers: { 'Accept': 'application/json' },
        })
            .then(res => res.json())
            .then(data => {
                if (data.authenticated) {
                    window.location.href = '/'
                }
            })
            .catch(() => { })
    }, [])

    // Register-Handler
    const handleRegister = async (e: React.FormEvent) => {
        e.preventDefault()
        setError('')
        setIsLoading(true)

        if (!vorname || !nachname || !email || !password || !passwordConfirm) {
            setError('Bitte alle Felder ausfüllen')
            setIsLoading(false)
            return
        }

        if (password !== passwordConfirm) {
            setError('Passwörter stimmen nicht überein')
            setIsLoading(false)
            return
        }

        if (password.length < 8) {
            setError('Passwort muss mindestens 8 Zeichen lang sein')
            setIsLoading(false)
            return
        }

        try {
            const response = await fetch(BACKEND_URL + '/register', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    vorname,
                    nachname,
                    email,
                    password,
                    password_confirmation: passwordConfirm
                })
            })

            const data = await response.json()
            if (response.ok && data.success) {
                const token = data.token
                localStorage.setItem('token', token)
                window.location.href = '/'
            } else {
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0]
                    setError(Array.isArray(firstError) ? firstError[0] : String(firstError))
                } else {
                    setError(data.message || 'Registrierung fehlgeschlagen')
                }
            }
        } catch (err) {
            console.error('Register error:', err)
            setError('Registrierung fehlgeschlagen')
        } finally {
            setIsLoading(false)
        }
    }

    return (
        <div className="flex items-center justify-center min-h-screen">
            <div className="w-full max-w-md p-8 space-y-6">
                <div className="text-center">
                    <Heading className="text-2xl">Registrieren</Heading>
                    <p className="mt-2 text-sm">Erstelle ein neues Konto</p>
                </div>

                <form onSubmit={handleRegister} className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                        <Field>
                            <Label>Vorname</Label>
                            <Input
                                type="text"
                                value={vorname}
                                onChange={(e) => setVorname(e.target.value)}
                                disabled={isLoading}
                                autoComplete="given-name"
                            />
                        </Field>

                        <Field>
                            <Label>Nachname</Label>
                            <Input
                                type="text"
                                value={nachname}
                                onChange={(e) => setNachname(e.target.value)}
                                disabled={isLoading}
                                autoComplete="family-name"
                            />
                        </Field>
                    </div>

                    <Field>
                        <Label>E-Mail</Label>
                        <Input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            disabled={isLoading}
                            autoComplete="email"
                        />
                    </Field>

                    <Field>
                        <Label>Passwort</Label>
                        <Input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            disabled={isLoading}
                            autoComplete="new-password"
                        />
                    </Field>

                    <Field>
                        <Label>Passwort bestätigen</Label>
                        <Input
                            type="password"
                            value={passwordConfirm}
                            onChange={(e) => setPasswordConfirm(e.target.value)}
                            disabled={isLoading}
                            autoComplete="new-password"
                        />
                    </Field>

                    {error && (
                        <div className="p-3 text-sm text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400 rounded-md">
                            {error}
                        </div>
                    )}

                    <Button type="submit" className="w-full" disabled={isLoading}>
                        {isLoading ? 'Registrieren...' : 'Registrieren'}
                    </Button>
                </form>

                <div className="text-center text-sm">
                    <span className="text-zinc-500 dark:text-zinc-400">Bereits ein Konto? </span>
                    <a href="/login" className="text-blue-600 dark:text-blue-400 hover:underline">
                        Anmelden
                    </a>
                </div>
            </div>
        </div>
    )
}
