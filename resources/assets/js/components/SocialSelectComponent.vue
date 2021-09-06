<template>
  <div class="social-select">

    <div class="selected" v-if="filterProfile.id" @click="showOther = !showOther">
      <img :src="filterProfile.social_avatar ? filterProfile.social_avatar : '/img/no_social.png'" :alt="filterProfile.social_username">

      <div class="name">
        <strong>
          {{ filterProfile.social_username }}
        </strong>

        <small class="text-muted">
          {{ types[filterProfile.social_type] }}
        </small>
      </div>
    </div>

    <div class="selected" @click="showOther = !showOther" v-else>
      <img src="/img/select_social.png">

      <div class="name">
        <strong>
          Выберите
        </strong>

        <small class="text-muted">
          или добавьте аккаунт
        </small>
      </div>
    </div>

    <div class="other" v-show="showOther">
      <ul>
        <li v-for="profile in profiles" :class="allowProfile(profile) ? '' : 'disabled'">
          <img :src="profile.social_avatar ? profile.social_avatar : '/img/no_social.png'" :alt="profile.social_username" @click="selectProfile(profile.id, allowProfile(profile))">
          <div class="name" :class="profile.id == socialSelect ? 'active' : ''">
            <strong>
              {{ profile.social_username }}
            </strong>

            <small class="text-muted">
              {{ types[profile.social_type] }}
            </small>

            <small class="text-danger" v-if="!profile.private && profile.open_data && !profile.active">Не подтвержден</small>
            <small class="text-danger" v-if="profile.private">Закрытый аккаунт</small>
            <small class="text-info" v-if="!profile.open_data">Просмотры закрыты</small>

          </div>
          <div class="controls text-right">
            <button type="button" class="btn btn-sm text-muted" title="Подтвердить аккаунт" v-if="!profile.active && profile.open_data && !profile.private" data-toggle="tooltip" :disabled="isLoading" @click="checkProfile(profile)">
              <i class="fas fa-user-times"></i>
            </button>

            <button type="button" class="btn btn-sm text-muted" title="Измените настройки аккаунта и обновите для разблокировки" v-if="!profile.open_data || profile.private" data-toggle="tooltip" @click="updateProfile(profile)" :disabled="isLoading">
              <i class="fas fa-sync-alt" :class="isLoading && thisLoad == profile.id ? 'fa-spin' : ''"></i>
            </button>

            <button type="button" class="btn btn-sm text-danger" title="Удалить" data-toggle="tooltip" @click="deleteProfile(profile)" :disabled="isLoading">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </li>

        <li v-if="error">
          <div class="alert alert-warning w-100 mb-0 text-center">
            {{ error }}
          </div>
        </li>

        <li class="add-new">
          <button type="button" class="btn btn-outline-success btn-sm btn-block text-center" v-show="!addNewProfile" @click="addNewProfile = true" :disabled="isLoading">
            Добавить аккаунт соцсети
          </button>

          <div class="add-new-block" v-show="addNewProfile">
            <select class="form-control" v-model="newType">
              <option :value="key" v-for="(item, key) in types">{{ item }}</option>
            </select>

            <input type="text" ref="username" class="form-control mt-3 mb-3" v-model="username" placeholder="@username">

            <button type="button" class="btn btn-outline-success btn-sm btn-block text-center" @click="addNew()" :disabled="isLoading">
              Проверить и добавить
            </button>
          </div>

        </li>

      </ul>

    </div>

    <!-- =================================== -->
    <modal name="checksProfile" @before-close="beforeCloseModal" :height="'auto'" :adaptive="true" :scrollable="true">
      <div class="row text-center">
        <div class="col-12">
          <h4>
            Проверка пользователя <strong>{{ checkedProfile.social_username }}</strong>
          </h4>
        </div>

        <div class="col-12" v-if="errorModal">
          <div class="alert alert-warning w-100 mb-0 text-center">
            {{ errorModal }}
          </div>
        </div>

        <div class="col-12 p-3" v-if="checkedListUser.social_username">
          <h6>
            Поставьте Like любой записи пользователя от имени <strong>{{ checkedProfile.social_username }}</strong> и нажмите <strong>"Проверить"</strong>
          </h6>
        </div>
      </div>

      <div class="row text-center" v-if="checkedListUser.social_username">
        <div class="col-5 p-3">
          <div>
            <img :src="checkedProfile.social_avatar" class="rounded-2">
            <br>
            <strong class="mt-3">@{{ checkedProfile.social_username }}</strong>
          </div>
        </div>

        <div class="col-2 align-self-center">
          <i class="fas fa-heart fa-3x text-danger"></i>
          <br>
          <i class="fas fa-long-arrow-alt-right fa-2x"></i>
        </div>

        <div class="col-5 p-3">
          <a :href="checkedLink" target="socialbooster_checked">
            <img :src="checkedListUser.social_avatar" class="rounded-2">
            <br>
            <strong class="mt-3">@{{ checkedListUser.social_username }}</strong>
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-12 pt-5">
          <button type="button" class="btn btn-warning rounded-1 btn-block text-white" :disabled="checkedListUser.length < 1 || isLoading" @click="getCheck()">
            Проверить
          </button>
        </div>
      </div>

    </modal>
    <!-- =================================== -->

  </div>
</template>

<script>

export default {
  name: 'SocialSelectComponent',
  props: ['profiles', 'types'],

  data(){
    return {
      socialSelect: 0,
      showOther: false,
      addNewProfile: false,
      isLoading: false,
      error: null,
      errorModal: null,

      newType: 'tiktok',
      username: '',

      checkedProfile: [],
      checkedListUser: [],
      checkedLink: '',

      thisLoad: 0,
    }
  },

  mounted: function() {
    this.reloadTooltip()
  },

  created() {
    if (typeof localStorage.socialSelect == 'undefined') {
      localStorage.socialSelect = 0
    }

    if (localStorage.socialSelect && this.profiles && this.profiles.length > 0) {
      const profile = this.profiles.find(item => {
        return item.id == localStorage.socialSelect
      })

      if (this.allowProfile(profile)) {
        this.socialSelect = localStorage.socialSelect
      } else {
        this.socialSelect = localStorage.socialSelect = 0
      }
    }

  },

  watch: {
    showOther() {
      if (this.showOther == false) {
        this.reloadTooltip()
        this.addNewProfile = false
        this.newType ='tiktok'
        this.username = ''
      }
    },

    error() {
      if (this.error !== null) {
        window.setInterval(()=>{
          this.error = null
        }, 8000)
      }
    },
  },

  computed: {
    filterProfile() {
      if (this.socialSelect) {
        return this.profiles.find(item => {
          return item.id == this.socialSelect
        })
      }
      return false
    },

    // socProfile() {
    //   return this.$store.state.socProfile
    // },

  },

  methods: {
    reloadTooltip() {
      jQuery(function () {
          jQuery('.tooltip').tooltip('dispose')
          jQuery('[data-toggle="tooltip"]').tooltip()
      })
    },

    beforeCloseModal() {
      this.checkedProfile = []
      this.checkedListUser = []
      this.checkedLink = ''
      // return false
    },

    checkProfile(profile) {
      this.showOther = false
      this.checkedProfile = profile
      this.errorModal = null

      // получаем профайл для проверки
      const URL = '/api/v2/socials/get'
      let data = new FormData()
      data.append('id', profile.id)
      data.append('type', profile.social_type)

      axios.post(URL, data).then(res => {
        if (res.data.success) {
          this.checkedListUser = res.data.checked
          this.checkedLink = res.data.link
        } else {
          this.errorModal = res.data.info
        }
      }).catch(error => {
        this.errorModal = 'Попробуйте позже еще раз'
      })

      this.$modal.show('checksProfile')
    },

    getCheck() {
      this.isLoading = true
      this.errorModal = null

      const URL = '/api/v2/socials/check'
      let data = new FormData()
      data.append('id', this.checkedProfile.id)
      data.append('type', this.checkedProfile.social_type)
      data.append('checked_id', this.checkedListUser.id)

      axios.post(URL, data).then(res => {
        if (res.data.success) {
          this.profiles[this.profiles.indexOf(this.checkedProfile)].active = 1
          this.$modal.hide('checksProfile')
        } else {
          this.errorModal = res.data.info
        }
        this.isLoading = false
      }).catch(error => {
        this.errorModal = 'Попробуйте позже еще раз'
        this.isLoading = false
      })

    },

    deleteProfile(profile) {
      this.isLoading = true
      this.error = null

      if (profile.id == this.socialSelect) {
        this.socialSelect = localStorage.socialSelect = 0
      }

      const URL = '/api/v2/socials/delete'
      let data = new FormData()
      data.append('id', profile.id)

      axios.post(URL, data).then(res => {
        this.error = res.data.info
        if (res.data.success) {
          this.profiles.splice(this.profiles.indexOf(profile), 1)
        } else {
          this.error = res.data.info
        }

        this.addNewProfile = false
        this.isLoading = false
      }).catch(error => {
        this.error = 'Попробуйте позже еще раз'
        this.addNewProfile = false
        this.isLoading = false
      })

      this.reloadTooltip()
    },

    updateProfile(profile) {
      this.isLoading = true
      this.error = null
      this.thisLoad = profile.id

      const URL = '/api/v2/socials/update'
      let data = new FormData()
      data.append('id', profile.id)

      axios.post(URL, data).then(res => {
        this.error = res.data.info
        if (res.data.success) {
          this.profiles[this.profiles.indexOf(profile)] = res.data.profile
        } else {
          this.error = res.data.info
        }

        this.isLoading = false
        this.thisLoad = 0
      }).catch(error => {
        this.error = 'Попробуйте позже еще раз'
        this.isLoading = false
        this.thisLoad = 0
      })

      this.reloadTooltip()
    },

    addNew() {
      if (this.username == '' || this.username == '@') {
        this.error = 'Не заполнены поля'
        this.$refs.username.focus()
        return false
      }

      this.isLoading = true
      this.error = null

      const URL = '/api/v2/socials/add'
      let data = new FormData()
      data.append('username', this.username)
      data.append('type', this.newType)

      axios.post(URL, data).then(res => {
        this.error = res.data.info
        if (res.data.success) {
          this.profiles.push(res.data.profile)
        }

        this.addNewProfile = false
        this.username = ''
        this.isLoading = false
      }).catch(error => {
        this.error = 'Попробуйте позже еще раз'
        this.addNewProfile = false
        this.username = ''
        this.isLoading = false
      })

      this.reloadTooltip()
    },

    //профайл можно использовать или нет
    allowProfile(profile) {
      //пустой профайл
      if (typeof profile == 'undefined') {
        return false
      }

      if (profile.active == 0) {
        return false
      }
      if (profile.private == 1) {
        return false
      }
      if (profile.open_data == 0) {
        return false
      }

      return true
    },

    selectProfile(id, canSelect) {
      if (!canSelect) {
        return false
      }

      this.socialSelect = localStorage.socialSelect = id
      this.showOther = false
    },


  },
}
</script>
