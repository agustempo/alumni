<template>
  <div class="data-table">
    <div class="main-table">
      <div v-if="filtro"> 
        <input class="input" type="input" v-model="parametros"  @change="fetchData()" placeholder="Filtrar"></input>
        
      </div>
      <table class="table is-hoverable">
        <thead>
        <tr>
          <th v-for="column in columns" :key="column" @click="sortByColumn(column)"
              class="table-head">
            {{ column | columnHead }}
            <span v-if="column === sortedColumn">
                            <i v-if="order == 'asc' " class="fas fa-arrow-up"></i>
                            <i v-else class="fas fa-arrow-down"></i>
            </span>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr class="" v-if="tableData.length === 0">
          <td class="lead text-center" :colspan="columns.length + 1">No hay registros.</td>
        </tr>
        <tr v-for="(data, key1) in tableData" :key="data.id" v-on:click="clickList(data.id)" class="m-datatable__row clickable-row" v-else>
          <td v-for="(value, key) in data">{{ value }}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <nav v-if="pagination && tableData.length > 0" class="pagination is-right" role="navigation" aria-label="pagination">

      <a class="pagination-previous" href="#" @click.prevent="changePage(currentPage - 1)"><</a>
      <a class="pagination-next" href="#" @click.prevent="changePage(currentPage + 1)">></a>


      <ul class="pagination-list">
        
        <li v-for="page in pagesNumber"
            :class="{'is-current': page == pagination.meta.current_page}">
          <a href="javascript:void(0)" @click.prevent="changePage(page)" class="pagination-link {'is-current': page == pagination.meta.current_page}">{{ page }}</a>
        </li>
        
        <span style="margin-top: 8px;"> &nbsp; <i>Mostrando {{ pagination.data.length }} de {{ pagination.meta.total }}</i></span>
      </ul>
    </nav>

  </div>
</template>

<script type="text/ecmascript-6">

import axios from 'axios';
export default {
  props: {
    fetchUrl: { type: String, required: true },
    columns: { type: Array, required: true },
    viewUrl: { type: String, required: true },
    filtro: { type: Boolean, required: false },
  },
  data() {
    return {
      tableData: [],
      url: '',
      pagination: {
        meta: { to: 1, from: 1 }
      },
      offset: 4,
      currentPage: 1,
      perPage: 50,
      sortedColumn: this.columns[0],
      order: 'asc',
      parametros: '',
    }
  },
  watch: {
    fetchUrl: {
      handler: function(fetchUrl) {
        this.url = fetchUrl
      },
      immediate: true
    }
  },
  created() {
    return this.fetchData()
  },
  computed: {
    /**
     * Get the pages number array for displaying in the pagination.
     * */
    pagesNumber() {
      if (!this.pagination.meta.to) {
        return []
      }
      let from = this.pagination.meta.current_page - this.offset
      if (from < 1) {
        from = 1
      }
      let to = from + (this.offset * 2)
      if (to >= this.pagination.meta.last_page) {
        to = this.pagination.meta.last_page
      }
      let pagesArray = []
      for (let page = from; page <= to; page++) {
        pagesArray.push(page)
      }
      return pagesArray
    },
    /**
     * Get the total data displayed in the current page.
     * */
    totalData() {
      return (this.pagination.meta.to - this.pagination.meta.from) + 1
    }
  },
  methods: {
    fetchData() {
      let dataFetchUrl = `${this.url}?page=${this.currentPage}&column=${this.sortedColumn}&order=${this.order}&per_page=${this.perPage}&filtro=${this.parametros}`
      axios.get(dataFetchUrl)
        .then(({ data }) => {
          this.pagination = data
          this.tableData = data.data
        }).catch(error => this.tableData = [])
    },
    /**
     * Get the serial number.
     * @param key
     * */
    serialNumber(key) {
      return (this.currentPage - 1) * this.perPage + 1 + key
    },
    /**
     * Change the page.
     * @param pageNumber
     */
    changePage(pageNumber) {
      this.currentPage = pageNumber
      this.fetchData()
    },
    /**
     * Sort the data by column.
     * */
    sortByColumn(column) {
      if (column === this.sortedColumn) {
        this.order = (this.order === 'asc') ? 'desc' : 'asc'
      } else {
        this.sortedColumn = column
        this.order = 'asc'
      }
      this.fetchData()
    },
    clickList: function (id) {
    window.location.href = this.viewUrl + id;
    }
  },
  filters: {
    columnHead(value) {
      return value.split('_').join(' ').toUpperCase()
    }
  },
  name: 'DataTable'
}
</script>

<style scoped>
</style>