<template>
	<v-app>
			<v-app-bar app clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>果丹皮后台管理系统</v-toolbar-title>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" :clipped="$vuetify.breakpoint.lgAndUp" app>
      <v-list dense>
        <template v-for="item in slidebarData">
          <v-row
            v-if="item.heading"
            :key="item.heading"
            align="center"
          >
            <v-col cols="6">
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-col>
            <v-col
              cols="6"
              class="text-center"
            >
              <a
                href="#!"
                class="body-2 black--text"
              >EDIT</a>
            </v-col>
          </v-row>
          <v-list-group
            v-else-if="item.children"
            :key="item.text"
            v-model="item.model"
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
            append-icon=""
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>
                    {{ item.text }}
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>
            <v-list-item
              v-for="(child, i) in item.children"
              :key="i"
              @click="xx"
            >
              <v-list-item-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
          <v-list-item
            v-else
            :key="item.text"
            @click="xx"
          >
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{ item.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-navigation-drawer>
    <v-content>
      <v-container class="fill-witdh" fluid>
        <v-tabs>
          <v-tab>全部</v-tab>
          <v-tab>待审核</v-tab>
          <v-tab>已通过</v-tab>
          <v-tab>已驳回</v-tab>
        </v-tabs>
        <v-simple-table>
          <template v-slot:default>
            <thead>
              <tr>
                <th class="text-left">名称</th>
                <th class="text-left">卡路里</th>
                <th class="text-left">卡路里</th>
                <th class="text-left">卡路里</th>
                <th class="text-left">卡路里</th>
                <th class="text-left">卡路里</th>
                <th class="text-left">卡路里</th>
                <th class="text-left">卡路里</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, key) in uploadList" :key="key">
                <td>{{ item.calories }}</td>
                <td>{{ item.calories }}</td>
                <td>{{ item.calories }}</td>
                <td>{{ item.calories }}</td>
                <td>{{ item.calories }}</td>
                <td>{{ item.calories }}</td>
                <td>{{ item.calories }}</td>
                <td>{{ item.calories }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
        <div class="text-center">
          <v-pagination
            v-model="page"
            :length="6"
          ></v-pagination>
        </div>
      </v-container>
    </v-content>
    <v-footer app>
      <span>&copy; 2019 果丹皮网络科技有限公司</span>
    </v-footer>
	</v-app>
</template>
<script>
export default {
	data() {
		return ({
      drawer: null,
      page: 2,
      uploadList: [
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
        {name: '果丹皮', calories: '1300'},
      ],
			slidebarData: [
				{
          icon: 'keyboard_arrow_up',
          'icon-alt': 'keyboard_arrow_down',
          text: '资源审核',
          model: false,
          children: [
						{ icon: 'content_copy', text: '用户发贴列表', routerName: 'uploadList' }
          ],
        },
			]
			// slidebarData: [
      //   { icon: 'contacts', text: 'Contacts' },
      //   { icon: 'history', text: 'Frequently contacted' },
      //   { icon: 'content_copy', text: 'Duplicates' },
      //   {
      //     icon: 'keyboard_arrow_up',
      //     'icon-alt': 'keyboard_arrow_down',
      //     text: 'Labels',
      //     model: true,
      //     children: [
      //       { icon: 'add', text: 'Create label' },
      //     ],
      //   },
      //   {
      //     icon: 'keyboard_arrow_up',
      //     'icon-alt': 'keyboard_arrow_down',
      //     text: 'More',
      //     model: false,
      //     children: [
      //       { text: 'Import' },
      //       { text: 'Export' },
      //       { text: 'Print' },
      //       { text: 'Undo changes' },
      //       { text: 'Other contacts' },
      //     ],
      //   },
      //   { icon: 'settings', text: 'Settings' },
      //   { icon: 'chat_bubble', text: 'Send feedback' },
      //   { icon: 'help', text: 'Help' },
      //   { icon: 'phonelink', text: 'App downloads' },
      //   { icon: 'keyboard', text: 'Go to the old version' },
      // ],
		})
	},
	methods: {
		xx(data) {
			console.log(data)
		}
	}
}
</script>