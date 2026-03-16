<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Pencil, Calendar, User, UserRound, Clock, FileText } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import appointmentsRoutes from '@/routes/appointments';
import doctorsRoutes from '@/routes/doctors';
import patientsRoutes from '@/routes/patients';
import type { BreadcrumbItem } from '@/types';

type Appointment = {
    id: number;
    doctor: {
        id: number;
        name: string;
        crm: string;
        specialty: string;
    };
    patient: {
        id: number;
        name: string;
        cpf: string;
    };
    user: string;
    scheduled_at: string;
    status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
    notes: string | null;
    created_at: string;
};

const props = defineProps<{
    appointment: Appointment;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agendamentos',
        href: appointmentsRoutes.index.url(),
    },
    {
        title: 'Ver Agendamento',
        href: appointmentsRoutes.show.url(props.appointment.id),
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
</script>

<template>
    <Head title="Ver Agendamento" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon-sm" as-child>
                        <Link :href="appointmentsRoutes.index.url()">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <h1 class="text-2xl font-bold">Detalhes do Agendamento</h1>
                </div>
                <Button as-child variant="outline">
                    <Link :href="appointmentsRoutes.edit.url(appointment.id)">
                        <Pencil class="mr-2 h-4 w-4" />
                        Editar
                    </Link>
                </Button>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <Card class="lg:col-span-2">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>Informações da Consulta</CardTitle>
                                <CardDescription>Detalhes sobre data, hora e status.</CardDescription>
                            </div>
                            <Badge :variant="getStatusBadge(appointment.status).variant" class="text-sm">
                                {{ getStatusBadge(appointment.status).label }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex items-start gap-3">
                                <Calendar class="mt-1 h-5 w-5 text-muted-foreground" />
                                <div>
                                    <label class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Data e Hora</label>
                                    <p class="text-lg font-semibold">{{ appointment.scheduled_at }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <Clock class="mt-1 h-5 w-5 text-muted-foreground" />
                                <div>
                                    <label class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Registrado em</label>
                                    <p class="font-medium">{{ appointment.created_at }}</p>
                                    <p class="text-sm text-muted-foreground">por {{ appointment.user }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <div class="flex items-start gap-3">
                                <FileText class="mt-1 h-5 w-5 text-muted-foreground" />
                                <div class="w-full">
                                    <label class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Observações</label>
                                    <p class="mt-1 whitespace-pre-wrap rounded-md bg-muted/50 p-4 text-sm leading-relaxed">
                                        {{ appointment.notes || 'Nenhuma observação registrada.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <User class="h-5 w-5 text-muted-foreground" />
                                Médico
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Link :href="doctorsRoutes.show.url(appointment.doctor.id)" class="group">
                                <h3 class="font-bold group-hover:text-primary transition-colors">{{ appointment.doctor.name }}</h3>
                                <p class="text-sm text-muted-foreground">{{ appointment.doctor.specialty }}</p>
                                <p class="mt-1 text-xs font-medium uppercase text-muted-foreground">CRM: {{ appointment.doctor.crm }}</p>
                            </Link>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <UserRound class="h-5 w-5 text-muted-foreground" />
                                Paciente
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Link :href="patientsRoutes.show.url(appointment.patient.id)" class="group">
                                <h3 class="font-bold group-hover:text-primary transition-colors">{{ appointment.patient.name }}</h3>
                                <p class="text-sm text-muted-foreground">CPF: {{ appointment.patient.cpf }}</p>
                            </Link>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
