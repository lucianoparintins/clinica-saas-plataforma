<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save, Loader2 } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { cn } from '@/lib/utils';
import InputError from '@/components/InputError.vue';
import appointmentsRoutes from '@/routes/appointments';
import type { BreadcrumbItem } from '@/types';

type Appointment = {
    id: number;
    doctor_id: number;
    patient_id: number;
    scheduled_at: string;
    status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
    notes: string | null;
};

type Doctor = {
    id: number;
    name: string;
};

type Patient = {
    id: number;
    name: string;
};

const props = defineProps<{
    appointment?: Appointment;
    doctors: Doctor[];
    patients: Patient[];
}>();

const isEditing = !!props.appointment;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Agendamentos',
        href: appointmentsRoutes.index.url(),
    },
    {
        title: isEditing ? 'Editar Agendamento' : 'Novo Agendamento',
        href: isEditing ? appointmentsRoutes.edit.url(props.appointment!.id) : appointmentsRoutes.create.url(),
    },
];

// Format date for datetime-local input (YYYY-MM-DDThh:mm)
const formatDateForInput = (dateString?: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().slice(0, 16);
};

const form = useForm({
    doctor_id: props.appointment?.doctor_id.toString() ?? '',
    patient_id: props.appointment?.patient_id.toString() ?? '',
    scheduled_at: formatDateForInput(props.appointment?.scheduled_at),
    status: props.appointment?.status ?? 'pending',
    notes: props.appointment?.notes ?? '',
});

const submit = () => {
    if (isEditing) {
        form.put(appointmentsRoutes.update.url(props.appointment!.id));
    } else {
        form.post(appointmentsRoutes.store.url());
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Editar Agendamento' : 'Novo Agendamento'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon-sm" as-child>
                    <Link :href="appointmentsRoutes.index.url()">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <h1 class="text-2xl font-bold">{{ isEditing ? 'Editar Agendamento' : 'Novo Agendamento' }}</h1>
            </div>

            <div class="max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Dados do Agendamento</CardTitle>
                        <CardDescription>
                            {{ isEditing ? 'Atualize as informações do agendamento.' : 'Preencha os campos para agendar uma nova consulta.' }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="space-y-2">
                                <Label for="doctor_id">Médico</Label>
                                <Select v-model="form.doctor_id">
                                    <SelectTrigger :error="!!form.errors.doctor_id">
                                        <SelectValue placeholder="Selecione um médico" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="doctor in doctors" :key="doctor.id" :value="doctor.id.toString()">
                                            {{ doctor.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.doctor_id" />
                            </div>

                            <div class="space-y-2">
                                <Label for="patient_id">Paciente</Label>
                                <Select v-model="form.patient_id">
                                    <SelectTrigger :error="!!form.errors.patient_id">
                                        <SelectValue placeholder="Selecione um paciente" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="patient in patients" :key="patient.id" :value="patient.id.toString()">
                                            {{ patient.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.patient_id" />
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="scheduled_at">Data e Hora</Label>
                                    <Input
                                        id="scheduled_at"
                                        type="datetime-local"
                                        v-model="form.scheduled_at"
                                        :error="!!form.errors.scheduled_at"
                                        required
                                    />
                                    <InputError :message="form.errors.scheduled_at" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="status">Status</Label>
                                    <Select v-model="form.status">
                                        <SelectTrigger :error="!!form.errors.status">
                                            <SelectValue placeholder="Selecione o status" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="pending">Pendente</SelectItem>
                                            <SelectItem value="confirmed">Confirmado</SelectItem>
                                            <SelectItem value="cancelled">Cancelado</SelectItem>
                                            <SelectItem value="completed">Concluído</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.status" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="notes">Observações</Label>
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    placeholder="Observações adicionais sobre a consulta..."
                                    :class="cn(
                                        'placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex min-h-[100px] w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs transition-[color,box-shadow] outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
                                        'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
                                        form.errors.notes ? 'border-destructive ring-destructive/20 dark:ring-destructive/40' : ''
                                    )"
                                ></textarea>
                                <InputError :message="form.errors.notes" />
                            </div>

                            <div class="flex justify-end gap-3 border-t pt-6">
                                <Button type="button" variant="ghost" as-child>
                                    <Link :href="appointmentsRoutes.index.url()">Cancelar</Link>
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    <template v-if="form.processing">
                                        <Loader2 class="mr-2 h-4 w-4 animate-spin" />
                                        Salvando...
                                    </template>
                                    <template v-else>
                                        <Save class="mr-2 h-4 w-4" />
                                        {{ isEditing ? 'Atualizar' : 'Salvar' }} Agendamento
                                    </template>
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
