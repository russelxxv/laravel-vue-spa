<template>
    <div class="container" v-if="isLoading">The Data is Loading ...</div>
    <div class="container" v-else>
      <div class="row mb-3" v-for="row in rows" :key="'row' + row">
        <div class="col d-flex align-items-stretch" v-for="(bookable, iCol) in bookablesInRow(row)" :key="'colrow'+iCol">
          <bookable-list-item
            v-bind="bookable"
          ></bookable-list-item>
        </div>
        <div class="col" v-for="p in placeholdersInRow(row)" :key="'place'+p+'d'"></div>
      </div>
    </div>
</template>

<script>
import BookableListItem from "./BookableListItem";
export default {
  data() {
    return {
      bookables: null,
      isLoading: false,
      columns: 3,
    };
  },
  components: {
    BookableListItem,
  },
  // beforeCreate () {
  //     console.log('before create');
  // },
  computed: {
    rows() {
      return this.bookables === null ? 0 : Math.ceil(this.bookables.length / this.columns);
    },
  },
  methods: {
    bookablesInRow(row) {
      return this.bookables.slice((row - 1) * this.columns, row * this.columns)
    },
    placeholdersInRow(row) {
      return this.columns - this.bookablesInRow(row).length;
    }
  },
  created() {
    this.isLoading = true;

    const request = axios
      .get('/api/bookables')
      .then( response => {
        this.bookables = response.data.data;
        this.isLoading = false;
      });

    // console.log(request);
  },
  // beforeMount () {
  //     console.log("beforeMount");
  // },
  // mounted() {
  //   console.log("mounted");
  // },
  // beforeDestroy() {
  //     console.log('Before Destroy');
  // },
  // destroyed(){
  //     console.log('Before destroy');
  // }
};
</script>
