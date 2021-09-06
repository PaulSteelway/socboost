<template>
  <div class="col-12 account-card">

    <!-- выборка по датам и отправителям -->
    <div class="row mt-3">

      <div class="col-12 alert alert-info">
        Выборка по сумме отображает максимум по 200 человек и игнорирует дату и статусы. Чтоб снова фильтровать по дате и статусу выберите "Не фильтровать по сумме"
      </div>

      <div class="col-12 mt-3">
        <h6>Выборка по дате</h6>
      </div>

      <div class="col-sm-12 col-md-5 d-flex">
        <date v-model="selectdate"></date>
        <div class="p-1">
          <button class="btn btn-sm btn-primary" :disabled="!buttonActive" @click="loadData()">Посчитать</button>
        </div>
      </div>

      <div class="col-sm-12 col-md-4 d-flex">
        <select class="form-control" v-model="more">
          <option :value="0">Не фильтровать по сумме</option>
          <option :value="index" v-for="(amount, index) in amounts">{{ amount }}</option>
        </select>
        <div class="p-1">
          <button class="btn btn-sm btn-primary" :disabled="!buttonActive" @click="loadMore()">Найти</button>
        </div>

      </div>

      <div class="col-sm-12 col-md-3 d-flex">
        <select class="form-control" v-model="status">
          <option :value="0">Все статусы</option>
          <option :value="status.id" v-for="status in statuses">{{ status.name }}</option>
        </select>
      </div>

    </div>

    <div class="row mt-3" style="min-height:50vh;">
      <div class="col-12 text-center" v-if="!loaded">
        <div class="fa-3x">
          <i class="fas fa-spinner fa-spin"></i>
        </div>
        <h4 class="text-red">загрузка данных...</h4>
      </div>

      <div class="col-12">

        <table class="table table-striped table-warning th-center" v-if="users.length > 0">
          <thead v-if="loaded">
            <tr>
              <th width="60">#</th>
              <th width="350">Email</th>
              <th>Кошелек</th>
              <th width="200">Баланс</th>
              <th width="60"></th>
            </tr>
          </thead>
          <tbody v-if="loaded">
            <tr v-for="(user, index) in users">
              <td>{{ index + 1 }}</td>
              <td>
                <div class="" v-if="user.user">
                  {{ user.user.email }}
                </div>
                <div class="" v-else>
                  - - -
                </div>
              </td>
              <td>
                <div class="" v-if="user.wallet">
                  {{ user.wallet.payment_system.code }}
                  <small class="clearfix">
                    {{ user.wallet.id }}
                  </small>
                </div>
                <div class="" v-if="user.balance">
                  {{ user.payment_system.code }}
                  <small class="clearfix">
                    {{ user.id }}
                  </small>
                </div>
                <div class="" v-else>
                  - - -
                </div>
              </td>
              <td class="text-right strong">
                <div class="" v-if="user.wallet">
                  {{ user.wallet.balance }} {{ user.wallet.currency.code }}
                </div>
                <div class="" v-if="user.balance">
                  {{ user.balance }} {{ user.currency.code }}
                </div>
                <div class="" v-else>
                  -
                </div>
              </td>
              <td class="text-center">
                <div class="" v-if="user.wallet && user.wallet.balance">
                  <button class="btn btn-sm btn-danger" :disabled="!posted" title="Обнулить баланс" @click="cleared(index, user.wallet.id)">
                    <i class="fas fa-broom"></i>
                  </button>
                </div>

                <div class="" v-if="user.balance">
                  <button class="btn btn-sm btn-danger" :disabled="!posted" title="Обнулить баланс" @click="cleared(index, user.id)">
                    <i class="fas fa-broom"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="w-100 text-center mt-5" v-else>
          <p class="h4" v-if="loaded">
            {{ error }}
          </p>
        </div>

      </div>
    </div>

  </div>
</template>

<script>

export default {
  name: 'RecalculateTransactionComponent',
  props: ['statuses'],

  data(){
    return {
      selectdate: [],
      status: 0,
      more: 0,


      error: 'Данные не найдены',
      loaded: true,
      posted: true,

      users: [],

      amounts: {
        '1000': 'Более 1 000',
        '2000': 'Более 2 000',
        '5000': 'Более 5 000',
        '10000': 'Более 10 000',
        '15000': 'Более 15 000',
        '100000': 'Много (более 100 000)',
      },

    }
  },

  created() {
    if (!this.selectdate.length > 0) {
      this.selectdate = [
        this.$moment().format('DD-MM-YYYY'),
        this.$moment().format('DD-MM-YYYY')
      ]
    }

    //очень долго сразу
    // this.loadData()
  },

  computed: {
    buttonActive() {
      return this.selectdate[0]
    },

  },

  methods: {

    cleared(index, id) {
      this.posted = false
      axios.post('recalculate/cleared', {
        id: id
      }).then(response => {
        this.posted = true

        if(response.data.success) {
          if (this.more == 0) {
            this.users[index].wallet.balance = response.data.balance
          } else {
            this.users[index].balance = response.data.balance
          }
        } else {
          this.error = 'Ошибка обнуления кошелька'
        }
      })
      .catch(error => {
        this.posted = true
      })
    },

    loadData() {
      this.loaded = false
      const [selectdate] = [this.selectdate];
      axios.post('', {
        status: this.status,
        selectdate
      })
      .then(response => {
        this.loaded = true

        if(response.data.success) {
          this.users = response.data.users
        } else {
          this.users = []
          this.error = 'Ошибка загрузки данных'
        }
      })
      .catch(error => {
        this.loaded = true
        this.users = []
      })

    },

    loadMore() {
      this.loaded = false
      axios.post('', {
        more: this.more
      })
      .then(response => {
        this.loaded = true

        if(response.data.success) {
          this.users = response.data.users
        } else {
          this.users = []
          this.error = 'Ошибка загрузки данных'
        }
      })
      .catch(error => {
        this.loaded = true
        this.users = []
      })

    },

  },
}
</script>
