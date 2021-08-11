<template>
      <v-row>
          <v-col cols="12" class="crm-table">
              <v-simple-table>
                  <template v-slot:default>
                  <thead>
                      <tr>
                          <th class="text-left">
                              Agency name
                          </th>
                          <th class="text-left">
                              Total leads
                          </th>
                          <th class="text-left">
                              Last updated
                          </th>
                          <th class="text-left">
                              Offices
                          </th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr
                      v-for="item in agency"
                      :key="item.name"
                      >
                      <td>{{ item.agencyname }}</td>
                      <td>{{ item.totalleads }}</td>
                      <td>{{ item.lastupdated }}</td>
                      <td>{{ item.Offices }}</td>
                      </tr>
                  </tbody>
                  </template>
              </v-simple-table>
          </v-col>
      </v-row>
</template>

<script>
export default {
    name: "CrmDataTable",
    data () {
      return {
        agency: [
          {
            agencyname: 'Barry Plant',
            totalleads: 500,
            lastupdated: '00/00/2021',
            Offices: 30,
          },
          {
            agencyname: 'Raine & Horne',
            totalleads: 100,
            lastupdated: '06/00/2021',
            Offices: 100,
          },
          {
            agencyname: '[EA Homes]_(Independent)',
            totalleads: 300,
            lastupdated: '07/00/2021',
            Offices: 30,
          },
          {
            agencyname: 'Raine & Horne',
            totalleads: 200,
            lastupdated: '08/00/2021',
            Offices: 10,
          },
        ],
      }
    },
  }
</script>

<style scoped>

</style>
