<template>
  <div>
    <v-app id="login">
      <v-main>
        <HeaderItem/>
        <v-container
          class="fill-height"
          fluid
        >
          <v-row
            align="center"
            justify="center"
          >
            <v-col
              cols="12"
              sm="8"
              md="4"
            >
              <v-card class="elevation-12" height="100%">
                <v-toolbar
                  color="orange"
                  dark
                  flat
                >
                  <v-toolbar-title>Login form</v-toolbar-title>
                  <v-spacer />
                </v-toolbar>
                <v-card-text>
                  <v-form>
                    <v-text-field
                      label="user_name"
                      name="user_name"
                      prepend-icon="email"
                      type="text"
                      v-model="user_name"
                    />

                    <v-text-field
                      id="password"
                      label="Password"
                      name="password"
                      prepend-icon="lock"
                      type="password"
                      v-model="password"
                    />
                  </v-form>
                </v-card-text>
                <v-card-actions>
                  <v-spacer />
                  <v-btn @click="postLogin" color="orange">Login</v-btn>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-main>
    </v-app>
  </div>
</template>

<script>
import HeaderItem from '../components/Header'

  export default {
      name: 'login',
      components : {
      HeaderItem
    },
    data () {
      return {
        user_name: "",
        password: ""
      }
    },
    methods: {
        postLogin(){
            axios.post(window.location.origin + `/login`, {
                user_name: this.user_name,
                password: this.password
            })
            .then(response => {
                window.location.href = window.location.origin + `/mypage`;
            })
            .catch(error => {
                console.log(error);
            });
        }
    }
  }
</script>