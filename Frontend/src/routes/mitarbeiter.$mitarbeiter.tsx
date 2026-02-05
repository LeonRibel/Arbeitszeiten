import { useState, useEffect } from "react";

interface EditMitarbeiterProps {
    params: {
        mitarbeiter: string
    }
}

interface MitarbeiterDaten {
    vorhandene_daten: {
        id: number;
        vorname: string;
        nachname: string;
        username: string;
    }
}


export default function EditMitarbeiter({ params }: EditMitarbeiterProps) {
    const [mitarbeiterDaten, setMitarbeiterDaten] = useState<MitarbeiterDaten>({ vorhandene_daten: {} });
    const [vorname, setVorname] = useState<string>('');
    const [nachname, setNachname] = useState<string>('');
    const [username, setUsername] = useState<string>('');

    useEffect(() => {
        fetch(`http://localhost/Mitarbeiter/update?id=${params.mitarbeiter}`, {
            credentials: "include",
            headers: {
                "Accept": "application/json",
            }
        })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                setMitarbeiterDaten(data);
                setVorname(data.vorhandene_daten?.vorname);
                setNachname(data.vorhandene_daten?.nachname);
                setUsername(data.vorhandene_daten?.username);

            })
            .catch(err => console.error('Fehler beim Laden der Mitarbeiterdaten:', err));
    }, [params.mitarbeiter]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('Vorname', vorname);
        formData.append('Nachname', nachname);
        formData.append('username', username);
        formData.append('id', String(mitarbeiterDaten.vorhandene_daten?.id ?? params.mitarbeiter ?? ''));

        console.log('Sende Daten:', {
            vorname: vorname,
            nachname: nachname,
            username: username,
            id: mitarbeiterDaten.vorhandene_daten?.id ?? params.mitarbeiter
        });

        try {
            const response = await fetch('http://localhost/Mitarbeiter/update', {
                method: 'POST',
                credentials: 'include',
                body: formData
            });

            if (response.ok) {
                window.location.href = '/Mitarbeiter';
            }
        } catch (err) {
            console.error('Fehler beim Aktualisieren des Mitarbeiters:', err);
        }
    };

    return (
    <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div className="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
                Mitarbeiter bearbeiten
            </h2>
        </div>

        <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form method="post" onSubmit={handleSubmit} className="space-y-6">
                <div>
                    <label htmlFor="vorname" className="block text-sm/6 font-medium text-gray-100">
                        Vorname
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
                        Nachname
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
                        Benutzername
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

                <input
                    type="hidden"
                    name="id"
                    value={mitarbeiterDaten.vorhandene_daten.id ?? params.mitarbeiter ?? ''}
                />

                <div>
                    <button
                        type="submit"
                        className="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                    >
                        Bestätigen
                    </button>
                </div>
            </form>

            <p className="mt-10 text-center text-sm/6 text-gray-400">
                <a href="/Mitarbeiter" className="font-semibold text-indigo-400 hover:text-indigo-300">
                    Zurück zum Dashboard
                </a>
            </p>
        </div>
    </div>
);
}
