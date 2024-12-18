//import vue router
import { createRouter, createWebHistory } from "vue-router";
const routes = [
  {
    path: "/",
    name: "home",
    component: () => import(/* webpackChunkName: "home" */ "../views/home.vue"),
  },
  {
    path: "/items",
    name: "items",
    component: () => import(/* webpackChunkName: "home" */ "../views/items.vue"),
  },
  {
    path: "/crud",
    name: "crud",
    component: () => import(/* webpackChunkName: "home" */ "../views/crud.vue"),
  },
  {
    path: "/crudproducts",
    name: "crud.products",
    component: () =>
      import(/* webpackChunkName: "index" */ "../views/products/index.vue"),
  },
  {
    path: "/crudcategories",
    name: "crud.categories",
    component: () =>
      import(/* webpackChunkName: "index" */ "../views/categories/index.vue"),
  },
];
//create router
const router = createRouter({
  history: createWebHistory(),
  routes, // <-- routes,
});
export default router;
