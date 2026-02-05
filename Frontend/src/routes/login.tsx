import { useState, useEffect,} from "react"
import { Heading } from "@catalyst/heading"
import { Field, Label } from "@catalyst/fieldset"
import { Input } from "@catalyst/input"
import { Button } from "@catalyst/button"
import { BACKEND_URL } from "../config"

export default function Login() {
    const [email, setEmail] = useState('')
    const [password, setPassword] = useState('')
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

    // Login-Handler
    const handleLogin = async (e: any) => {
        e.preventDefault()
        setError('')
        setIsLoading(true)

        if (!email || !password) {
            setError('Bitte alle Felder ausfüllen')
            setIsLoading(false)
            return
        }

        try {
            const response = await fetch(BACKEND_URL + '/login', { // <-- API Endpoint
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, password }) // JSON Body
            })

            const data = await response.json()
            if (response.ok && data.success) {

                const token = data.token

                localStorage.setItem('token', token);

                window.location.href = '/' // Redirect nach Login
            } else {
                setError(data.message || 'Login fehlgeschlagen')
            }
        } catch (err) {
            console.error('Login error:', err)
            setError('Login fehlgeschlagen')
        } finally {
            setIsLoading(false)
        }
    }

    return (
        <div className="flex items-center justify-center min-h-screen">
            <div className="w-full max-w-md p-8 space-y-6">
                <div className="text-center">
                    <Heading className="text-2xl">Login</Heading>
                    <p className="mt-2 text-sm">Bitte melde dich an</p>
                </div>

                <form onSubmit={handleLogin} className="space-y-4">
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
                        <Label>Password</Label>
                        <Input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            disabled={isLoading}
                            autoComplete="current-password"
                        />
                    </Field>

                    {error && (
                        <div className="p-3 text-sm text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400 rounded-md">
                            {error}
                        </div>
                    )}

                    <Button type="submit" className="w-full" disabled={isLoading}>
                        {isLoading ? 'Logging in...' : 'Login'}
                    </Button>
                </form>

                <div className="text-center text-sm">
                    <a href="/settings" className="text-blue-600 dark:text-blue-400 hover:underline">
                        Passwort vergessen?
                    </a>
                </div>
            </div>
        </div>
    )
}
