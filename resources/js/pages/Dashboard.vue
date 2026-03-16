<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import appointmentsRoutes from '@/routes/appointments';
import type { BreadcrumbItem } from '@/types';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Calendar, Users, Stethoscope, Clock, CheckCircle2, XCircle, AlertCircle, ArrowRight } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

type Appointment = {
    id: number;
    doctor_name: string;
    patient_name: string;
    scheduled_at: string;
    status: string;
};

type Activity = {
    id: number;
    doctor_name: string;
    patient_name: string;
    user_name: string;
    status: string;
    updated_at: string;
    scheduled_at: string;
};

type Stats = {
    total_appointments: number;
    pending_appointments: number;
    confirmed_appointments: number;
    cancelled_appointments: number;
};

defineProps<{
    appointmentsOfWeek: Appointment[];
    recentActivities: Activity[];
    stats: Stats;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];

const getStatusBadge = (status: string) => {
    switch (status) {
        case 'pending': return { label: 'Pendente', variant: 'outline' as const, icon: AlertCircle };
        case 'confirmed': return { label: 'Confirmado', variant: 'default' as const, icon: CheckCircle2 };
        case 'cancelled': return { label: 'Cancelado', variant: 'destructive' as const, icon: XCircle };
        case 'completed': return { label: 'Concluído', variant: 'secondary' as const, icon: CheckCircle2 };
        default: return { label: status, variant: 'outline' as const, icon: AlertCircle };
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total de Consultas</CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_appointments }}</div>
                        <p class="text-xs text-muted-foreground">Histórico total do sistema</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Confirmadas</CardTitle>
                        <CheckCircle2 class="h-4 w-4 text-primary" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.confirmed_appointments }}</div>
                        <p class="text-xs text-muted-foreground">Consultas prontas para atendimento</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Pendentes</CardTitle>
                        <Clock class="h-4 w-4 text-warning" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.pending_appointments }}</div>
                        <p class="text-xs text-muted-foreground">Aguardando confirmação</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Canceladas</CardTitle>
                        <XCircle class="h-4 w-4 text-destructive" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.cancelled_appointments }}</div>
                        <p class="text-xs text-muted-foreground">Consultas removidas da agenda</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-7">
                <!-- Agenda da Semana -->
                <Card class="col-span-4">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>Agenda da Semana</CardTitle>
                                <CardDescription>Próximas consultas para os próximos 7 dias.</CardDescription>
                            </div>
                            <Button variant="ghost" size="sm" as-child>
                                <Link :href="appointmentsRoutes.index.url()">
                                    Ver tudo
                                    <ArrowRight class="ml-2 h-4 w-4" />
                                </Link>
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="appointment in appointmentsOfWeek" :key="appointment.id" class="flex items-center justify-between rounded-lg border p-4 transition-colors hover:bg-muted/50">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <Calendar class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ appointment.patient_name }}</p>
                                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                            <Stethoscope class="h-3 w-3" />
                                            {{ appointment.doctor_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold">{{ appointment.scheduled_at }}</p>
                                    <Badge :variant="getStatusBadge(appointment.status).variant" class="mt-1">
                                        {{ getStatusBadge(appointment.status).label }}
                                    </Badge>
                                </div>
                            </div>
                            <div v-if="appointmentsOfWeek.length === 0" class="flex flex-col items-center justify-center py-10 text-center text-muted-foreground">
                                <Calendar class="mb-2 h-10 w-10 opacity-20" />
                                <p>Nenhuma consulta agendada para esta semana.</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Atividades Recentes -->
                <Card class="col-span-3">
                    <CardHeader>
                        <CardTitle>Atividades Recentes</CardTitle>
                        <CardDescription>Últimas ações realizadas no sistema.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-6">
                            <div v-for="activity in recentActivities" :key="activity.id" class="flex gap-4">
                                <div class="relative flex h-8 w-8 shrink-0 items-center justify-center rounded-full border bg-background shadow-sm">
                                    <component :is="getStatusBadge(activity.status).icon" :class="['h-4 w-4', activity.status === 'cancelled' ? 'text-destructive' : 'text-primary']" />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm font-medium leading-none">
                                        <span class="font-bold">{{ activity.user_name }}</span>
                                        {{ activity.status === 'cancelled' ? 'cancelou' : 'agendou' }} consulta
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Paciente: {{ activity.patient_name }} | Médico: {{ activity.doctor_name }}
                                    </p>
                                    <div class="mt-1 flex items-center gap-2 text-[10px] uppercase tracking-wider text-muted-foreground">
                                        <span>{{ activity.updated_at }}</span>
                                        <span>•</span>
                                        <span>Para {{ activity.scheduled_at }}</span>
                                    </div>
                                </div>
                            </div>
                            <div v-if="recentActivities.length === 0" class="flex flex-col items-center justify-center py-10 text-center text-muted-foreground">
                                <Clock class="mb-2 h-10 w-10 opacity-20" />
                                <p>Nenhuma atividade recente encontrada.</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
