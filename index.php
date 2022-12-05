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
            <div class="col-12 mt-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                        data-bs-target="#createBox">
                    Nova Caixinha
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <MODAL-CREATE-BOX :box="boxEditing" @edit="editCaixinha" @update-box="updateBox"
                      @create-box="addCaixinha"></MODAL-CREATE-BOX>
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
                boxEditing: {},
                caixinha: {
                    titulo: '',
                    meta: '',
                    guardado: ''
                },
                caixinhas: [{
                    titulo: 'Poupança 1',
                    meta: '1.000,00',
                    guardado: '500,00'
                },
                    {
                        titulo: 'Poupança 2',
                        meta: '1.000,00',
                        guardado: '500,00'
                    },
                    {
                        titulo: 'Poupança 3',
                        meta: '1.000,00',
                        guardado: '500,00'
                    },
                    {
                        titulo: 'Poupança 4',
                        meta: '100,00',
                        guardado: '500,00'
                    }
                ]
            }
        },
        methods: {
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
                    guardado: caixinha.guardado
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
          <div class="col-md-6 mt-1">
          <div class="card">
            <div class="card-body box">
              <h5 class="card-title">{{ caixinha.titulo }}</h5>
              <div class="row">
                <span class="col-6"><p class="card-text">Meta:</p></span> <span class="col-6"><input class="money-text" disabled :value="'R$ ' + caixinha.meta"></span>
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
                <small class="text-muted">9 mins</small>
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
    app.component("modal-create-box", {
        props: ["box", 'index'],
        template: `<div class="modal fade" id="createBox" tabindex="-1" aria-labelledby="createBoxModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="createBoxModalLabel">Modal title</h5>
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
              <label for="titulo">Título</label> <input type="text" class="form-control" id="titulo"
                                                        v-model="box.titulo">
            </div>
            <div class="form-group mt-2">
              <label for="meta">Meta</label> <input type="text" class="form-control money" id="meta"
                                                    v-model="box.meta">
            </div>
            <div class="form-group mt-2">
              <label for="guardado">Guardado</label> <input type="text" class="form-control money"
                                                            id="guardado" v-model="box.guardado">
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
                const result = new Intl.NumberFormat('pt-BR', options).format(
                    parseFloat(value) / 100
                )
                return result
            }
        }
    })
    app.mount('#app');
</script>
</body>

</html>