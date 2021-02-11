<template>
    <div>
        <v-app id="mypage">
            <v-main>
                <HeaderItem/>
                <v-container class="fill-height" fluid>
                
                    <v-form style="width:100%" action="/regist" ref="settings" name="settings" method="post" @submit.prevent>
                        <h1>マイページ</h1>
                        <!-- DIGタイプ -->
                        <v-row align="start" align-content="start" justify="center">
                            <v-col cols="3" align-self="center">
                                <v-subheader>DIGタイプ</v-subheader>
                            </v-col>
                            <v-col cols="9" align-self="center">
                                <v-radio-group row mandatory name="dig_type" @change="change_dig_type">
                                    <v-radio
                                        v-for="(dig_type, idx) in dig_types"
                                        :key="idx"
                                        :label="dig_type.type"
                                        :value="dig_type.value"
                                        :checked="dig_type.checked"
                                        class="mb-0"
                                    ></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>
                        <hr>
                        <!-- 連携情報 -->
                        <v-row align="start" align-content="start" justify="center">
                            <v-col cols="3" align-self="center">
                                <v-subheader>連携情報</v-subheader>
                            </v-col>
                            <v-col cols="9" align-self="center">
                                <v-radio-group row mandatory name="link_type" @change="change_link_type">
                                    <v-radio
                                        v-for="(link_type, idx) in link_types"
                                        :key="idx"
                                        :label="link_type.type"
                                        :value="link_type.value"
                                        :id="'link_type' + idx"
                                        :disabled="link_type.disabled"
                                        :checked="link_type.checked"
                                        class="mb-0"
                                        :class="checkLink"
                                    ></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>
                        <hr>
                        <!-- 連携期間 -->
                        <v-row align="center">
                        <v-col cols="3">
                            <v-subheader>連携期間</v-subheader>
                        </v-col>
                        <v-col cols="9">
                            <v-select name="link_period" 
                                v-model="terms_select" 
                                :items="items" 
                                item-text="terms" 
                                item-value="value" 
                                label="選択" 
                                value="overall"
                                @change="change_period"
                                single-line></v-select>
                        </v-col>
                        </v-row>
                        <hr>
                        <v-row align="center">
                            <v-col cols="12" align-self="center">
                                <v-btn type="submit" @click="submit" style="align-center" color="orange">送信</v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
            </v-main>
        </v-app>
    </div>
</template>

<script>
import HeaderItem from '../components/Header'

export default {
    name: 'mypage',
    components : {
    HeaderItem
  },
  data () {
      return {
        dig_type_value: 0, // dig_type初期値
        link_type: null,
        link_type_value: 0, // link_type初期値
        link_period_value: 'overall', // link_period初期値
        success: false,
        dig_types: [
            { type: 'アーティスト単位で通知する', value: 'artists', checked: true},
            { type: '曲単位で通知する', value: 'songs', checked: false }
        ],
        link_types: [
            { type: 'アーティスト', value: 'artists', disabled: false, checked: true},
            { type: '楽曲', value: 'songs', disabled: false, checked: false },
            { type: 'Loveトラック', value: 'loves', disabled: false, checked: false }
        ],
        terms_select: { terms: '全期間', value: 'overall' },
        items: [
          { terms: '全期間', value: 'overall' },
          { terms: '1週間', value: '7day' },
          { terms: '1ヵ月', value: '1month' },
          { terms: '3ヵ月', value: '3month' },
          { terms: '6ヵ月', value: '6month' },
          { terms: '1年間', value: '12month' },
        ]
      }
  },
  methods: {
    logincheck() {
        const api_url = "/sessionCheck";
        axios.get(api_url).then(response => {
            if(!response.data) {
                window.location.href = 'https://www.last.fm/api/auth?api_key=' + api_key;
            }
        }).catch(function(error) {
            console.log('ERROR!! occurred in Backend.')
        });
    },
    // DIG TYPE選択時
    change_dig_type(select_dig_type) {
        this.dig_type_value = select_dig_type;
        switch(select_dig_type) {
            case 'artists':
                // アーティストを選択可
                this.link_types[0].disabled = false;
                this.link_types[1].disabled = true;
                this.link_types[2].disabled = true;
                // 連携情報はアーティストになる
                if(this.link_type_value != 0) {
                    this.link_types[0].checked = true;
                    this.link_types[1].checked = false;
                    this.link_types[1].checked = false;
                    this.link_type_value = 0;
                }
                break;
            case 'songs':
                // 楽曲、Loveトラックを選択可
                this.link_types[0].disabled = true;
                this.link_types[1].disabled = false;
                this.link_types[2].disabled = false;
                // 連携情報は楽曲になる
                if(this.link_type_value == 0) {
                    this.link_types[0].checked = false;
                    this.link_types[1].checked = true;
                    this.link_types[2].checked = false;
                    this.link_type_value = 1;
                }
                break;
        }
    },
    // LINK TYPE選択時
    change_link_type(select_link_type) {
        this.link_type_value = select_link_type;
    },
    // LINK TYPE選択時
    change_period(link_period_value) {
        this.link_period_value = link_period_value;
    },
    submit() {
        if (this.$refs.settings.validate()) {

            this.success = true;
            //let params = new URLSearchParams();
            //var input_data = document.createElement('input');
            //input_data.name='_token';
            //input_data.type='hidden';
            //input_data.value=window.Laravel['csrfToken'];
            //var formData = document.getElementsByTagName('form');
            //formData[0].appendChild(input_data);
            //params.append('_token', input_data.value);
            axios.post('/regist', {
                dig_type: this.dig_type_value,
                link_type: this.link_type_value,
                link_period: this.link_period_value
            })
            .then(function (response) {
                // 格好いいポップアップにしたい
                console.log(response);
                alert('結果を送信しました');
            });
            // TODO:selectの初期値がobjectなのを解決したい
            //document.settings.submit();
        } else {
            this.success = false;
        }
    }
  },
  computed: {
    checkLink() {
        // ラジオ変えたい
        return 'v-item--active';
    }
  },
  created () {
    this.logincheck();
  }
}
</script>
<style>
/* labelにmarginがついてしまうので解消(良い方法あれば変えたい) */
label {
  margin-bottom: 0;
}
</style>