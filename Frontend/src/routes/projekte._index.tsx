import { useState, useEffect } from "react";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@catalyst/table";
import { Link } from "react-router-dom";
import { CalendarDaysIcon, PlusCircleIcon } from '@heroicons/react/24/outline';
import fetchApi from "../fetchApi";

interface Projekt {
    id: number;
    aufgabe: string;
    // Kunde kommt jetzt als Objekt (oder null)
    kunde?: { id: number; firmenname: string } | null;
    status: string;
    gesamt: number;
}

export default function ProjekteTabelle() {
    // State
    const [projekte, setProjekte] = useState<Projekt[]>([]);
    const [ladeStatus, setLadeStatus] = useState(true);

    // Daten laden
    useEffect(() => {
        fetchApi("/projekte")
            .then((daten: any) => {
                console.log("API Response:", daten);
                // Handle different response formats
                if (Array.isArray(daten)) {
                    setProjekte(daten);
                } else if (daten && Array.isArray(daten.data)) {
                    setProjekte(daten.data);
                } else if (daten && typeof daten === 'object') {
                    console.error("Unerwartetes Antwortformat:", daten);
                    setProjekte([]);
                } else {
                    console.error("Keine Projekte gefunden");
                    setProjekte([]);
                }
            })
            .catch((err) => {
                console.error("Fehler beim Laden der Projekte:", err);
                setProjekte([]);
            })
            .finally(() => setLadeStatus(false));
    }, []);

    // Formatiere Betrag in Euro
    const formatBetrag = (betrag: number) => {
        return new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'EUR'
        }).format(betrag);
    };

    // Status-Badge-Farbe
    const getStatusFarbe = (status: string) => {
        return status === 'aktiv'
            ? 'bg-zinc-900 text-orange-600 font-semibold'
            : 'bg-zinc-900 text-green-600 font-semibold';
    };

    if (ladeStatus) return <div>Lädt…</div>;

    return (
        <div className="space-y-4">
            {/* Header */}
            <div className="flex justify-between items-center">
                <h2 className="text-lg font-semibold">Projekte</h2>
                <Link
                    to="/projekte/neu"
                    className="inline-flex items-center gap-2 px-4 py-2 bg-white-600 hover:bg-white-700 text-white rounded-lg transition-colors"
                >
                    <PlusCircleIcon className="w-5 h-5" />
                    Neues Projekt
                </Link>
            </div>

            {/* Catalyst Tabelle */}
            <Table striped>
                <TableHead>
                    <TableRow>
                        <TableHeader>Aufgabe</TableHeader>
                        <TableHeader>Kunde</TableHeader>
                        <TableHeader>Status</TableHeader>
                        <TableHeader className="text-right">
                            Budget
                        </TableHeader>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {projekte.map((projekt) => (
                        <TableRow key={projekt.id}>
                            <TableCell className="font-medium">{projekt.aufgabe}</TableCell>
                            <TableCell>{projekt.kunde ? projekt.kunde.firmenname : '—'}</TableCell>
                            <TableCell>
                                <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusFarbe(projekt.status)}`}>
                                    {projekt.status}
                                </span>
                            </TableCell>
                            <TableCell className="text-right font-medium">{formatBetrag(projekt.gesamt)}</TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </div>
    );
}
