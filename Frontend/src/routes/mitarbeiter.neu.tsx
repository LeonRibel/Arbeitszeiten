import { useState } from "react";
import { useTranslation } from 'react-i18next';

export default function NeuMitarbeiter() {
    const { t } = useTranslation();
    const [vorname, setVorname] = useState<string>('');
    const [nachname, setNachname] = useState<string>('');
    const [username, setUsername] = useState<string>('');
    const [password, setPasswort] = useState<string>('');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('Vorname', vorname);
        formData.append('Nachname', nachname);
        formData.append('username', username);
        formData.append('password', password);

        console.log('Sende neue Daten:', {
            vorname,
            nachname,
            username,
            passwort: '***'
        });

        try {
            const response = await fetch('http://localhost/Mitarbeiter/update', {
                method: 'POST',
                credentials: 'include',
                body: formData
            });

            console.log('Response Status:', response.status);
            const responseData = await response.text();
            console.log('Response Data:', responseData);

            if (response.ok) {
                window.location.href = '/Mitarbeiter';
            } else {
                alert(t('saveError') + ': ' + response.status + ' - ' + responseData);
            }
        } catch (err) {
            console.error('Fehler beim Erstellen des Mitarbeiters:', err);
            alert(t('networkError') + ': ' + err);
        }
    };

    return (
        <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
                    {t('newEmployees')}
                </h2>
            </div>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form method="post" onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label htmlFor="vorname" className="block text-sm/6 font-medium text-gray-100">
                            {t('firstName')}
                        </label>
                        <div className="mt-2">
                            <input
                                id="vorname"
                                type="text"
                                name="vorname"
                                required
                                value={vorname}
                                onChange={(e) => setVorname(e.target.value)}
                                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <div>
                        <label htmlFor="nachname" className="block text-sm/6 font-medium text-gray-100">
                            {t('lastName')}
                        </label>
                        <div className="mt-2">
                            <input
                                id="nachname"
                                type="text"
                                name="nachname"
                                required
                                value={nachname}
                                onChange={(e) => setNachname(e.target.value)}
                                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <div>
                        <label htmlFor="username" className="block text-sm/6 font-medium text-gray-100">
                            {t('username')}
                        </label>
                        <div className="mt-2">
                            <input
                                id="username"
                                type="text"
                                name="username"
                                required
                                value={username}
                                onChange={(e) => setUsername(e.target.value)}
                                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <div>
                        <label htmlFor="passwort" className="block text-sm/6 font-medium text-gray-100">
                            {t('password')}
                        </label>
                        <div className="mt-2">
                            <input
                                id="passwort"
                                type="password"
                                name="passwort"
                                required
                                value={password}
                                onChange={(e) => setPasswort(e.target.value)}
                                className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                            />
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            className="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                        >
                            {t('create')}
                        </button>
                    </div>
                </form>

                <p className="mt-10 text-center text-sm/6 text-gray-400">
                    <a href="/Mitarbeiter" className="font-semibold text-indigo-400 hover:text-indigo-300">
                        {t('backToDashboard')}
                    </a>
                </p>
            </div>
        </div>
    );
}