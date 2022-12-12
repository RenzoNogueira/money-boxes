<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixinhas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<div id="app">
    <div class="container d-flex align-items-center justify-content-center pt-5">
        <div class="row">
            <CAIXINHA v-for="(caixinha, index) in caixinhas" :key="index" :caixinha="caixinha" :index="index"
                      @remove="removeCaixinha" @edit="editCaixinha"></CAIXINHA>
        </div>

        <div class="d-flex flex-column position-fixed bottom-0 end-0 m-4">
            <!-- Botão para acionar modal de cadastro -->
            <button type="button" class="btn btn-success btn-floating mb-2 d-none" id="add-box"
                    data-bs-target="#createBox" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Nova Caixinha" @click="openModalCreateCaixinha">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-success btn-floating" @click="toogleMenu"
                    data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Ações">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Modal -->
    <MODAL-CREATE-BOX :box="boxEditing" @edit="editCaixinha" @update-box="updateBox"
                      @create-box="addCaixinha"></MODAL-CREATE-BOX>

    <AUTH v-show="auth == false" @save-user="saveUser"></AUTH>

    <div class="alerts" style="position: fixed; top: 0; right: 0; z-index: 9999; color: white">
        <alert v-for="(alert, index) in alerts" :key="index" :alert="alert" @remove="removeAlert"></alert>
    </div>
</div>
<script src="https://kit.fontawesome.com/274af9ab8f.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    const {
        createApp
    } = Vue;
    const app = createApp({
        data() {
            return {
                auth: false,
                alerts: [],
                user: {},
                boxEditing: {
                    meta: '0,00',
                    guardado: '0,00',
                },
                caixinhas: [{
                    id: 1,
                    titulo: 'Poupança 1',
                    meta: '1.000,00',
                    guardado: '500,00',
                    date: new Date("2022-12-05T12:00:00"),
                },
                    {
                        id: 2,
                        titulo: 'Poupança 2',
                        meta: '1.000,00',
                        guardado: '500,00',
                        date: new Date("2022-12-03T12:00:00"),
                    },
                    {
                        id: 3,
                        titulo: 'Poupança 3',
                        meta: '1.000,00',
                        guardado: '500,00',
                        date: new Date("2022-12-02T12:00:00"),
                    },
                    {
                        id: 4,
                        titulo: 'Poupança 4',
                        meta: '100,00',
                        guardado: '500,00',
                        date: new Date("2022-11-12T12:00:00"),
                    }
                ]
            }
        },
        methods: {
            toogleMenu() {
                $('#add-box').toggleClass('d-none');
            },
            updateBox(caixinha, index) {
                this.caixinhas[index].titulo = caixinha.titulo
                this.caixinhas[index].meta = caixinha.meta
                this.caixinhas[index].guardado = caixinha.guardado
                $('.money').mask("#.##0,00", {reverse: true});
            },

            addCaixinha(caixinha) {
                const SELF = this
                SELF.caixinhas.push({
                    titulo: caixinha.titulo,
                    meta: caixinha.meta,
                    guardado: caixinha.guardado,
                    date: new Date()
                });
                SELF.caixinha.titulo = '';
                SELF.caixinha.meta = '';
                SELF.caixinha.guardado = '';
                SELF.boxEditing = SELF.caixinha
            },

            removeCaixinha(index) {
                this.caixinhas.splice(index, 1);
            },

            editCaixinha(box) {
                const index = box.index
                box = box.box
                this.boxEditing.titulo = box.titulo
                this.boxEditing.meta = box.meta
                this.boxEditing.guardado = box.guardado
                this.boxEditing.index = index
                $("#createBox").modal("show")
            },

            openModalCreateCaixinha() { // Abre o modal de criar caixinha
                this.boxEditing = { // Limpa os campos do modal
                    meta: '0,00',
                    guardado: '0,00',
                }
                $("#createBox").modal("show")
            },

            removeAlert(index) {
                this.alerts.splice(index, 1);
            },

            addAlert(alert) {
                const SELF = this
                SELF.alerts.push(alert);
                // Se tiver mais de dois alertas, remove o primeiro
                if (SELF.alerts.length > 2) {
                    SELF.alerts.splice(0, 1);
                }
                const removeAlert = setInterval(() => {
                    SELF.alerts.splice(0, 1);
                    clearInterval(removeAlert)
                }, 5000)
            },

            saveUser(dataUser) { // Savar o usuário no Cookie
                const SELF = this
                $.post('php/auth.php', {
                    auth: dataUser
                }, function (data) {
                    data = JSON.parse(data)
                    if (data.success) {
                        SELF.auth = true
                        SELF.user = data.success
                        console.log(SELF.user)
                        // Sava em COOKIE com a data limite de  uma semana
                        document.cookie = `user=${JSON.stringify(SELF.user)}; expires=${new Date(new Date().getTime() + 7 * 24 * 60 * 60 * 1000)}; path=/`
                        SELF.redirectLogin()
                    } else {
                        SELF.addAlert({
                            type: 'danger',
                            message: 'Erro ao logar!'
                        })
                    }
                })
            },

            getUser() { // Pega o usuário do COOKIE
                const SELF = this
                const cookies = document.cookie.split(';')
                if (cookies.length > 0) {
                    cookies.forEach(cookie => {
                        // Se existir o cookie user e não estiver logado
                        if (cookie.includes('user')) {
                            SELF.user = JSON.parse(cookie.split('=')[1])
                        }
                    })
                    // Se existir o Cookie, e não tiver parâmetros na url, redirecionar para a página do autênticar usuário.
                    if (SELF.user.username && SELF.user.name && !window.location.href.includes('user')) {
                        SELF.auth = true
                        SELF.redirectLogin()
                    }
                }
            },
            redirectLogin() {
                console.log(this.user)
                window.location.href = window.location.href + '?user=' + this.user.username
            }
        },

        mounted() {
            const SELF = this
            SELF.getUser(); // Recupera o usuário
            window.addEventListener('load', () => {
                let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })

                // Veridica se existe o parâmetro de auteenticação na URL
                let urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('user')) {
                    // Verifica se o usuário é o mesmo que está logado analisando o COOKIE
                    if (SELF.user.username !== urlParams.get('user')) {
                        SELF.auth = false
                        SELF.user = {}
                        document.cookie = `user=; expires=${new Date(new Date().getTime() - 1)}; path=/`
                        window.location.href = window.location.href.split('?')[0]
                    } else {
                        SELF.auth = true
                    }
                } else {
                    this.auth = false
                    $("#auth").modal("show")
                    $('.modal-backdrop').css('opacity', '0.92')
                }
            })
            // Desativa o click com o botão direito do mouse
            $(document).bind("contextmenu", function (e) {
                e.preventDefault();
                console.log('Clique direito desativado')
                // Pega o id do elemento clicado
                // TODO: Implemetar a função de editar caixinha pelo clique direito
                const id = e.target.id
                console.log(id)
            });
        }
    });

    // Componente de login
    app.component('auth', {
        template: `
          <div class="modal fade" id="auth" tabindex="-1" aria-labelledby="authLabel" aria-hidden="true"
               data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="authLabel">Autenticação</h5>
              </div>
              <div class="modal-body">
                <div class="mb-3 user">
                  <label for="authInputUserName" class="form-label">Usuário</label>
                  <input type="text" class="form-control" id="authInputUserName" placeholder="Usuário"
                         v-model="auth.user">
                </div>
                <div class="mb-3 password">
                  <label for="authInputPassword" class="form-label">Senha</label>
                  <input type="password" class="form-control" id="authInputPassword" placeholder="Senha"
                         v-model="auth.password">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="authUser">Autenticar</button>
              </div>
            </div>
          </div>
          </div>
        `,
        data() {
            return {
                auth: {
                    user: '',
                    password: ''
                }
            }
        },
        methods: {
            authUser() {
                const SELF = this
                SELF.saveUser(this.auth.user)
            },
            saveUser(dataUser) {
                const SELF = this
                SELF.$emit('save-user', SELF.auth)
            },
        },
    })
    app.component('caixinha', {
        props: ['caixinha', 'index'],
        data() {
            return {
                edit: false
            }
        },
        template: `
          <div class="col-md-6 mt-1">
          <div class="card caixinha" :id="caixinha.id">
            <div class="card-body box">
              <h5 class="card-title">{{ caixinha.titulo }}</h5>
              <div class="row">
                <span class="col-6"><p class="card-text">Meta:</p></span> <span class="col-6"><input class="money-text"
                                                                                                     disabled
                                                                                                     :value="'R$ ' + caixinha.meta"></span>
              </div>
              <div class="row">
                <span class="col-6"><p class="card-text">Guardado: R$</p></span> <span class="col-6"><input
                  class="money-text" disabled :value="'R$ ' + caixinha.guardado"></span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group mt-3">
                  <button type="button" class="btn mx-1 btn-sm btn-outline-secondary" @click="editar">Editar
                  </button>
                  <button type="button" class="btn mx-1 btn-sm btn-outline-secondary" @click="$emit('remove', index)">
                    Remover
                  </button>
                </div>
                <!-- Calcula a diferença entre a data de hoje e a data da caixinha, incluindo os meses e anos -->
                <small
                    class="text-muted">{{ Math.floor((caixinha.date - new Date()) / (1000 * 60 * 60 * 24)) * -1 !== 0 ? Math.floor((caixinha.date - new Date()) / (1000 * 60 * 60 * 24)) * -1 + ' dia(s)' : 'Hoje' }}</small>
              </div>
            </div>
          </div>
          </div>
        `,
        methods: {
            editar() {
                this.$emit('edit', {'box': this.caixinha, 'index': this.index})
            }
        }
    });
    app.component('modal-create-box', {
        props: ["box"],
        template: `
          <div class="modal fade" id="createBox" tabindex="-1" aria-labelledby="createBoxModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="createBoxModalLabel">{{ box.index != undefined ? 'Editar' : 'Criar' }}
                  caixinha</h5>
                <button type="button" class="btn" data-bs-dismiss="modal">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
              <div class="modal-body">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Adicionar nova poupança</h5>
                    <form>
                      <div class="form-group mt-2">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" id="titulo"
                               v-model="box.titulo" placeholder="Título">
                      </div>
                      <div class="form-group mt-2">
                        <label for="meta">Meta</label>
                        <input type="text" class="form-control money" id="meta"
                               v-model="box.meta" placeholder="Meta">
                      </div>
                      <div class="form-group mt-2">
                        <label for="guardado">Guardado</label>
                        <input type="text" class="form-control money"
                               id="guardado" v-model="box.guardado" placeholder="Guardado">
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button v-if="box.titulo" type="button" data-bs-dismiss="modal" @click="update" class="btn btn-success">
                  Salvar
                </button>
                <button v-else type="button" data-bs-dismiss="modal" @click="create" class="btn btn-success">Adicionar
                </button>
              </div>
            </div>
          </div>
          </div>
        `,

        watch: {
            box: { // Quando os campos forem alterados, aplicar máscara
                handler: function () {
                    this.box.meta = this.moneyMaskInput(this.box.meta)
                    this.box.guardado = this.moneyMaskInput(this.box.guardado)
                },
                deep: true // Para observar objetos dentro de objetos
            }
        },

        methods: {
            create() {
                this.$emit("createBox", this.box)
            },
            update() {
                this.box.meta = this.moneyMaskInput(this.box.meta)
                this.box.guardado = this.moneyMaskInput(this.box.guardado)
                this.$emit("updateBox", this.box, this.box.index)
            },

            moneyMaskInput(value) { // Aplica máscara de dinheiro no input
                value = value.replace('.', '').replace(',', '').replace(/\D/g, '')
                const options = {minimumFractionDigits: 2}
                return new Intl.NumberFormat('pt-BR', options).format(
                    parseFloat(value) / 100
                )
            }
        }
    })
    app.component('alert', {
        props: ['alert'],
        template: `
          <div class="alert alert-{{ alert.type }} alert-dismissible fade show" role="alert">
          {{ alert.message }}
          <button type="button" class="btn" data-bs-dismiss="alert">
            <i class="fa-solid fa-xmark"></i>
          </button>
          </div>
        `
    })
    app.mount('#app');
</script>
</body>

</html>