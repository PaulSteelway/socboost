<template>
  <div class="col-12 account-card">



  </div>
</template>

<script>

export default {
  name: 'CategoryComponent',
  // props: ['cards_list', 'user_id'],

  data(){
    return {
      // selectdate: [],
      // tomorrow: this.$moment().add(-1, 'days').format('DD-MM-YYYY'),
      // today: this.$moment().format('DD-MM-YYYY'),
      //
      // payments: [],
      // error: 'Данные за текущий период по этой карте не найдены',
      // selectcard: null,
      // selecttype: 0,
      // inputStr: '',
      // comments: [],
      // loaded: true,
    }
  },

  created() {
  },

  computed: {
    // total() {
    //   if (!this.payments.length > 0) {return 0}
    //   return this.payments.reduce(function (total, item) {
    //     return total + Number(item.value)
    //   }, 0);
    // },

  },

  methods: {
    // addComent(text) {
    //   this.comments = text
    // },


  },
}
</script>
