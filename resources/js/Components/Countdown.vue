<template>
    <span class="countdown">{{lTimeFortmated}}</span>
</template>

<script>

import {DateTime} from "luxon";

export default {
    props: ['time'],
    data(){
      return {
          lTime: null,
          lTimeFortmated: null
      }
    },
    methods: {

    },
    created() {
        let $v = this;
        $v.lTime = DateTime.fromISO(this.time);
        setInterval(function(){
            $v.lTimeFortmated = $v.lTime.diff(DateTime.now()).toFormat('hh:mm:ss');
        },500);
    }

}
</script>

<style scoped lang="scss">
@import "resources/css/admin-vars";
.countdown{
    font-size: 1.5em;
}
</style>
