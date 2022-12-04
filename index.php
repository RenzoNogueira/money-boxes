<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixinhas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>

<body>
    <div id="app">
        <div class="container">
            <div class="row">
                <caixinha v-for="(caixinha, index) in caixinhas" :key="index" :caixinha="caixinha" :index="index" @remove="removeCaixinha" @edit="editCaixinha"></caixinha>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Adicionar nova poupança</h5>
                            <form @submit.prevent="addCaixinha">
                                <div class="form-group">
                                    <label for="titulo">Título</label>
                                    <input type="text" class="form-control" id="titulo" v-model="caixinha.titulo">
                                </div>
                                <div class="form-group">
                                    <label for="meta">Meta</label>
                                    <input type="text" class="form-control" id="meta" v-model="caixinha.meta">
                                </div>
                                <div class="form-group">
                                    <label for="guardado">Guardado</label>
                                    <input type="text" class="form-control" id="guardado" v-model="caixinha.guardado">
                                </div>
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        const {
            createApp
        } = Vue;
        const app = createApp({
            data() {
                return {
                    caixinha: {
                        titulo: '',
                        meta: '',
                        guardado: ''
                    },
                    caixinhas: [{
                            titulo: 'Poupança 1',
                            meta: 'R$ 1.000,00',
                            guardado: 'R$ 500,00'
                        },
                        {
                            titulo: 'Poupança 2',
                            meta: 'R$ 1.000,00',
                            guardado: 'R$ 500,00'
                        },
                        {
                            titulo: 'Poupança 3',
                            meta: 'R$ 1.000,00',
                            guardado: 'R$ 500,00'
                        },
                        {
                            titulo: 'Poupança 4',
                            meta: 'R$ 1.000,00',
                            guardado: 'R$ 500,00'
                        }
                    ]
                }
            },
            methods: {
                addCaixinha() {
                    const SELF = this
                    SELF.caixinhas.push({
                        titulo: SELF.caixinha.titulo,
                        meta: SELF.caixinha.meta,
                        guardado: SELF.caixinha.guardado
                    });
                    SELF.caixinha.titulo = '';
                    SELF.caixinha.meta = '';
                    SELF.caixinha.guardado = '';
                },
                removeCaixinha(index) {
                    this.caixinhas.splice(index, 1);
                },
                editCaixinha(index, caixinha) {
                    this.caixinhas.splice(index, 1, caixinha);
                }
            }
        });
        app.component('caixinha', {
            props: ['caixinha', 'index'],
            data() {
                return {
                    edit: false
                }
            },
            template: `
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ caixinha.titulo }}</h5>
                            <p class="card-text">Meta: {{ caixinha.meta }}</p>
                            <p class="card-text">Guardado: {{ caixinha.guardado }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="edit = true">Editar</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="$emit('remove', index)">Remover</button>
                                </div>
                                <small class="text-muted">9 mins</small>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            methods: {
                updateCaixinha() {
                    const SELF = this
                    this.$emit('edit', this.index, {
                        titulo: SELF.caixinha.titulo,
                        meta: SELF.caixinha.meta,
                        guardado: SELF.caixinha.guardado
                    });
                    this.edit = false;
                }
            }
        });
        app.mount('#app');
    </script>
</body>

</html>