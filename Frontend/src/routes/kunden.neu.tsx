import { useState } from "react";
import { useTranslation } from 'react-i18next';
import fetchApi from "../fetchApi";

export default function NeuKunden() {
    const { t } = useTranslation();
    const [firmenname, setFirmenname] = useState<string>('');
    const [ansprechpartner, setAnsprechpartner] = useState<string>('');
    const [email, setEmail] = useState<string>('');
    const [telefon, setTelefon] = useState<string>('');
    const [ort, setOrt] = useState<string>('');
    const [straße, setStraße] = useState<string>('');
    const [hausnummer, setHausnummer] = useState<string>('');
    const [plz, setPlz] = useState<string>('');
    const [land, setLand] = useState<string>('DE');
    const [ust_id, setUst_id] = useState<string>('');
    const [handelsregister_id, setHandelsregister_id] = useState<string>('');
    const [kundenart, setKundenart] = useState<string>('B2B');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('firmenname', firmenname);
        formData.append('ansprechpartner', ansprechpartner);
        formData.append('email', email);
        formData.append('telefon', telefon);
        formData.append('ort', ort);
        formData.append('straße', straße);
        formData.append('hausnummer', hausnummer);
        formData.append('plz', plz);
        formData.append('land', land);
        formData.append('ust_id', ust_id);
        formData.append('handelsregister_id', handelsregister_id);
        formData.append('kundenart', kundenart);

        try {
            await fetchApi('/kunden', {
                method: 'POST',
                body: formData
            });

            window.location.href = '/kunden';
        } catch (err) {
            console.error('Fehler beim Erstellen des Kunden:', err);
            alert(t('networkError') + ': ' + err);
        }
    };

    return (
        <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div className="sm:mx-auto sm:w-full sm:max-w-2xl">
                <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-white">
                    {t('newCustomer')}
                </h2>
            </div>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-2xl">
                <form method="post" onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {/* Firmenname */}
                        <div>
                            <label htmlFor="firmenname" className="block text-sm/6 font-medium text-gray-100">
                                {t('companyName')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="firmenname"
                                    type="text"
                                    name="firmenname"
                                    required
                                    value={firmenname}
                                    onChange={(e) => setFirmenname(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Ansprechpartner */}
                        <div>
                            <label htmlFor="ansprechpartner" className="block text-sm/6 font-medium text-gray-100">
                                {t('contactPerson')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="ansprechpartner"
                                    type="text"
                                    name="ansprechpartner"
                                    required
                                    value={ansprechpartner}
                                    onChange={(e) => setAnsprechpartner(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Email */}
                        <div>
                            <label htmlFor="email" className="block text-sm/6 font-medium text-gray-100">
                                {t('email')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Telefon */}
                        <div>
                            <label htmlFor="telefon" className="block text-sm/6 font-medium text-gray-100">
                                {t('phone')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="telefon"
                                    type="tel"
                                    name="telefon"
                                    required
                                    value={telefon}
                                    onChange={(e) => setTelefon(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Straße */}
                        <div>
                            <label htmlFor="straße" className="block text-sm/6 font-medium text-gray-100">
                                {t('street')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="straße"
                                    type="text"
                                    name="straße"
                                    required
                                    value={straße}
                                    onChange={(e) => setStraße(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Hausnummer */}
                        <div>
                            <label htmlFor="hausnummer" className="block text-sm/6 font-medium text-gray-100">
                                {t('houseNumber')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="hausnummer"
                                    type="text"
                                    name="hausnummer"
                                    required
                                    value={hausnummer}
                                    onChange={(e) => setHausnummer(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* PLZ */}
                        <div>
                            <label htmlFor="plz" className="block text-sm/6 font-medium text-gray-100">
                                {t('postalCode')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="plz"
                                    type="text"
                                    name="plz"
                                    required
                                    value={plz}
                                    onChange={(e) => setPlz(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Ort */}
                        <div>
                            <label htmlFor="ort" className="block text-sm/6 font-medium text-gray-100">
                                {t('city')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="ort"
                                    type="text"
                                    name="ort"
                                    required
                                    value={ort}
                                    onChange={(e) => setOrt(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Land */}
                        <div>
                            <label htmlFor="land" className="block text-sm/6 font-medium text-gray-100">
                                {t('country')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="land"
                                    type="text"
                                    name="land"
                                    required
                                    value={land}
                                    onChange={(e) => setLand(e.target.value)}
                                    maxLength={2}
                                    placeholder="DE"
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Kundenart */}
                        <div>
                            <label htmlFor="kundenart" className="block text-sm/6 font-medium text-gray-100">
                                {t('customerType')}
                            </label>
                            <div className="mt-2">
                                <select
                                    id="kundenart"
                                    name="kundenart"
                                    required
                                    value={kundenart}
                                    onChange={(e) => setKundenart(e.target.value)}
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                >
                                    <option value="B2B">B2B</option>
                                    <option value="B2C">B2C</option>
                                </select>
                            </div>
                        </div>

                        {/* USt-ID */}
                        <div>
                            <label htmlFor="ust_id" className="block text-sm/6 font-medium text-gray-100">
                                {t('vatId')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="ust_id"
                                    type="text"
                                    name="ust_id"
                                    value={ust_id}
                                    onChange={(e) => setUst_id(e.target.value)}
                                    placeholder="DE123456789"
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        {/* Handelsregister-ID */}
                        <div>
                            <label htmlFor="handelsregister_id" className="block text-sm/6 font-medium text-gray-100">
                                {t('commercialRegister')}
                            </label>
                            <div className="mt-2">
                                <input
                                    id="handelsregister_id"
                                    type="text"
                                    name="handelsregister_id"
                                    value={handelsregister_id}
                                    onChange={(e) => setHandelsregister_id(e.target.value)}
                                    placeholder="HRB 12345"
                                    className="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                />
                            </div>
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
                    <a href="/kunden" className="font-semibold text-indigo-400 hover:text-indigo-300">
                        {t('backToCustomer')}
                    </a>
                </p>
            </div>
        </div>
    );
}
