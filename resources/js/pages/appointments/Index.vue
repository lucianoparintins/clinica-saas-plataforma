<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Eye, Calendar } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import appointmentsRoutes from '@/routes/appointments';
import type { BreadcrumbItem } from '@/types';

type AppointmentList = {
    id: number;
    doctor_name: string;
    patient_name: string;
    scheduled_at: string;
    status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
    notes?: string;
};

type PaginatedAppointments = {
    data: AppointmentList[];
    links: any[];
};

defineProps<{
    appointments: PaginatedAppointments;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agendamentos',
        href: appointmentsRoutes.index.url(),
    },
];

const getStatusBadge = (status: string) => {
    switch (status) {
        case 'pending': return { label: 'Pendente', variant: 'outline' as const };
        case 'confirmed': return { label: 'Confirmado', variant: 'default' as const };
        case 'cancelled': return { label: 'Cancelado', variant: 'destructive' as const };
        case 'completed': return { label: 'Concluído', variant: 'secondary' as const };
        default: return { label: status, variant: 'outline' as const };
    }
};

const deleteAppointment = (appointment: AppointmentList) => {
    if (confirm(`Tem certeza que deseja remover o agendamento de ${appointment.patient_name} com ${appointment.doctor_name}?`)) {
        router.delete(appointmentsRoutes.destroy.url(appointment.id));
    }
};
</script>

<template>
    <Head title="Agendamentos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Agendamentos</h1>
                <Button as-child>
                    <Link :href="appointmentsRoutes.create.url()">
                        <Plus class="mr-2 h-4 w-4" />
                        Novo Agendamento
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Próximas Consultas</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Data/Hora</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Médico</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Paciente</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                <tr v-for="appointment in appointments.data" :key="appointment.id" class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle font-medium">
                                        <div class="flex items-center">
                                            <Calendar class="mr-2 h-4 w-4 text-muted-foreground" />
                                            {{ appointment.scheduled_at }}
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle">{{ appointment.doctor_name }}</td>
                                    <td class="p-4 align-middle">{{ appointment.patient_name }}</td>
                                    <td class="p-4 align-middle">
                                        <Badge :variant="getStatusBadge(appointment.status).variant">
                                            {{ getStatusBadge(appointment.status).label }}
                                        </Badge>
                                    </td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="ghost" size="icon-sm" as-child title="Ver detalhes">
                                                <Link :href="appointmentsRoutes.show.url(appointment.id)">
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon-sm" as-child title="Editar">
                                                <Link :href="appointmentsRoutes.edit.url(appointment.id)">
                                                    <Pencil class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="ghost" size="icon-sm" @click="deleteAppointment(appointment)" class="text-destructive hover:text-destructive" title="Remover">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="appointments.data.length === 0">
                                    <td colspan="5" class="p-4 text-center text-muted-foreground">
                                        Nenhum agendamento encontrado.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="appointments.links.length > 3" class="mt-4 flex justify-center gap-1">
                        <template v-for="(link, k) in appointments.links" :key="k">
                            <div v-if="link.url === null" class="px-3 py-1 text-sm text-muted-foreground border rounded" v-html="link.label"></div>
                            <Link v-else :href="link.url" class="px-3 py-1 text-sm border rounded hover:bg-accent" :class="{ 'bg-primary text-primary-foreground': link.active }" v-html="link.label"></Link>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
