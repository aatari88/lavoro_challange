import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { toast } from 'sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tipo de Cambio',
        href: '/tipo-cambio',
    },
];

type TipoCambioItem = {
    fecha: string;
    moneda: string;
    compra: number;
    venta: number;
};

export default function TipoCambio() {

    const today = new Date().toISOString().split('T')[0];
    const [from, setFrom] = useState(today);
    const [to, setTo] = useState(today);
    const [datadb, setDatadb] = useState<TipoCambioItem[]>([]);

    const handleSearch = async() => {
        if (!from || !to) {
            toast.warning('Seleccione un rango válido');
            return;
        }
    
        if (new Date(from) > new Date(to)) {
            toast.error('La fecha "hasta" no puede ser menor a "desde"');
            return;
        }
        
        try {
            const response = await fetch(`/tipo-cambio/rango?from=${from}&to=${to}`);
            const data = await response.json();
            setDatadb(data);
        }
        catch (error) {
            console.error('Error fetching tipo cambio:', error);
        }
    }

    const handleExport = async() => {
        if (!from || !to || datadb.length === 0) {
            toast.warning('No hay datos para exportar');
            return;
        }
    
        const url = `/tipo-cambio/exportar?from=${from}&to=${to}`;
        window.open(url, '_blank');
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tipo Cambio" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex flex-wrap items-end gap-4">
                    <div>
                        <label className="text-sm font-semibold">Desde</label>
                        <input
                            type="date"
                            value={from}
                            onChange={(e) => setFrom(e.target.value)}
                            className="mt-1 w-full rounded-lg border border-gray-300 bg-white p-2 text-gray-900 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        />
                    </div>
                    <div>
                        <label className="text-sm font-semibold">Hasta</label>
                        <input
                            type="date"
                            value={to}
                            onChange={(e) => setTo(e.target.value)}
                            className="mt-1 w-full rounded-lg border border-gray-300 bg-white p-2 text-gray-900 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        />
                    </div>
                    <button
                        onClick={handleSearch}
                        className="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        Buscar
                    </button>
                    <button
                        onClick={handleExport}
                        // disabled={datadb.length === 0}
                        className={'rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700'}
                        // className={`rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700 ${datadb.length === 0 ? 'opacity-50 cursor-not-allowed' : ''}`}
                    >
                        Descargar Excel
                    </button>
                </div>

                <div className="mt-6 overflow-x-auto rounded-lg border shadow">
                    <table className="w-full table-auto border-collapse text-left text-sm">
                        <thead className="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th className="px-4 py-2 text-gray-700 dark:text-gray-200">Fecha</th>
                                <th className="px-4 py-2 text-gray-700 dark:text-gray-200">Moneda</th>
                                <th className="px-4 py-2 text-gray-700 dark:text-gray-200">Compra</th>
                                <th className="px-4 py-2 text-gray-700 dark:text-gray-200">Venta</th>
                            </tr>
                        </thead>
                        <tbody>
                            {datadb.length === 0 ? (
                                <tr>
                                    <td colSpan={3} className="px-4 py-4 text-center text-gray-500">
                                        No hay datos para el rango seleccionado.
                                    </td>
                                </tr>
                            ) : (
                                datadb.map((item) => (
                                    <tr key={item.fecha} className="hover:bg-gray-50">
                                        <td className="px-4 py-2 text-gray-700 dark:text-gray-200">{item.fecha}</td>
                                        <td className="px-4 py-2 text-gray-700 dark:text-gray-200">{item.moneda}</td>
                                        <td className="px-4 py-2 text-gray-700 dark:text-gray-200">{item.compra}</td>
                                        <td className="px-4 py-2 text-gray-700 dark:text-gray-200">{item.venta}</td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </AppLayout>
    );
}
