<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Pencil } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import doctorsRoutes from '@/routes/doctors';
import type { BreadcrumbItem } from '@/types';

type Doctor = {
    id: number;
    name: string;
    email: string;
    crm: string;
    specialty: string;
    phone: string;
    address: string | null;
    created_at: string;
};

const props = defineProps<{
    doctor: Doctor;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Médicos',
        href: doctorsRoutes.index.url(),
    },
    {
        title: 'Ver Médico',
        href: doctorsRoutes.show.url(props.doctor.id),
    },
];
</script>

<template>
    <Head title="Ver Médico" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon-sm" as-child>
                        <Link :href="doctorsRoutes.index.url()">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <h1 class="text-2xl font-bold">Detalhes do Médico</h1>
                </div>
                <Button as-child variant="outline">
                    <Link :href="doctorsRoutes.edit.url(doctor.id)">
                        <Pencil class="mr-2 h-4 w-4" />
                        Editar
                    </Link>
                </Button>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Informações Profissionais</CardTitle>
                        <CardDescription>Dados básicos e registro profissional.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Nome Completo</label>
                            <p class="text-lg font-semibold">{{ doctor.name }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">CRM</label>
                                <p class="font-medium">{{ doctor.crm }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Especialidade</label>
                                <p class="font-medium">{{ doctor.specialty }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Cadastrado em</label>
                            <p class="font-medium text-muted-foreground">{{ doctor.created_at }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Contato e Endereço</CardTitle>
                        <CardDescription>Como localizar o médico.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">E-mail</label>
                            <p class="font-medium">{{ doctor.email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Telefone</label>
                            <p class="font-medium">{{ doctor.phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground text-xs uppercase tracking-wider">Endereço</label>
                            <p class="font-medium">{{ doctor.address || 'Não informado' }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
