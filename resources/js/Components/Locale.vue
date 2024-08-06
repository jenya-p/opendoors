<template>
    <div>
        <span v-if="locale == 'ru'">Ру</span>
        <a v-else @click="set('ru')">Ру</a>
        <span v-if="locale == 'en'">En</span>
        <a v-else @click="set('en')">En</a>
    </div>
</template>

<script>

export default {
    data(){
        return {
            locale: this.$page.props.locale
        }
    },
    methods:{
      set(v){
          this.locale = v;
          let $v = this;

          axios.get(route('set-locale', v)).then(function(){
              $v.$inertia.reload({
                  onSuccess(){
                      console.log(1111);
                      $v.$i18n.locale = v;
                  }
              });
          });

      }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/admin-vars";
div{display: flex; gap: 10px}
span{color: $base-color; padding: 5px;}
a{
    color: $dark-shadow-color;
    text-decoration: none;
    transition: color 200ms ease;
    padding: 5px;

    &:hover{
        color: $fore-color
    }

}

</style>
