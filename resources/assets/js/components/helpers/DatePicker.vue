<template>
  <div>
    <date-picker
      v-model="selectdate"
      lang="ru"
      format="DD-MM-YYYY"
      range
      type="date"
      first-day-of-week=1
      :disabled-date="(date) => date <= disabledBefore"

      value-type="format"
      :shortcuts="shortcuts"
      ></date-picker>

      <input type="hidden" :value="isChange">
  </div>
</template>

<script>
import DatePicker from 'vue2-datepicker'
import pickerRu from 'vue2-datepicker/locale/ru'

export default {
  components: { DatePicker },
  props: ['value', 'dud'],
  model: {
      prop: 'value',
      event: 'change'
  },
  data() {
    return {
      selectdate: this.value,
      startDate: this.$moment("01-10-2019", "DD-MM-YYYY"),
      endDate: this.$moment().add(-1, 'days'),
      nowDate: new Date(),
      today: this.$moment().format('DD-MM-YYYY'),
      tomorrow: this.$moment().add(-1, 'days').format('DD-MM-YYYY'),

      disabledBefore: this.$moment("01-10-2019", "DD-MM-YYYY"),

      // custom range shortcuts
      shortcuts: [
        {
          text: 'Сегодня',
          onClick: () => {
            this.selectdate = [
              this.today,
              this.today
            ]
          }
        },
        {
          text: 'Вчера',
          onClick: () => {
            this.selectdate = [
              this.tomorrow,
              this.tomorrow
            ]
          }
        },
        {
          text: 'Этот месяц',
          onClick: () => {
            this.selectdate = [
              this.$moment().startOf('month').format('DD-MM-YYYY'),
              this.tomorrow
            ]
          }
        },
        {
          text: 'Прошлый',
          onClick: () => {
            this.selectdate = [
              this.$moment().add(-1, 'months').startOf('month').format('DD-MM-YYYY'),
              this.$moment().add(-1, 'months').endOf('month').format('DD-MM-YYYY')
            ]
          }
        },
      ],

    }
  },

  created() {
    if (!this.selectdate.length > 0) {
      this.selectdate = [
        this.tomorrow, this.tomorrow
      ]
    }

    if (this.dud) {
      this.disabledBefore = this.$moment(this.$moment().startOf('month').add(-1, 'days'), "DD-MM-YYYY")

      this.shortcuts = [
        {
          text: 'Квартал',
          onClick: () => {
            this.selectdate = [
              this.$moment().startOf('quarter').format('DD-MM-YYYY'),
              this.$moment().format('DD-MM-YYYY')
            ]
          }
        },
        {
          text: 'Вчера',
          onClick: () => {
            this.selectdate = [
              this.tomorrow,
              this.tomorrow
            ]
          }
        },
        {
          text: 'Этот месяц',
          onClick: () => {
            this.selectdate = [
              this.$moment().startOf('month').format('DD-MM-YYYY'),
              this.tomorrow
            ]
          }
        },
      ]
    } else {
      this.disabledBefore = this.$moment("01-10-2019", "DD-MM-YYYY")
    }

  },

  computed: {
    isChange() {
      this.$emit('change', this.selectdate);
      return this.selectdate;
    },
  },

  methods: {
    disabledDate(date, currentValue) {
      const firstDate = currentValue[0]
    },
  },
}
</script>
