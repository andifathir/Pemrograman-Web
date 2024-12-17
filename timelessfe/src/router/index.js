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
    path: "/postsproducts",
    name: "posts.index",
    component: () =>
      import(/* webpackChunkName: "index" */ "../views/products/index.vue"),
  },
  {
    path: "/createproducts",
    name: "posts.create",
    component: () =>
      import(/* webpackChunkName: "create" */ "../views/products/create.vue"),
  },
  {
    path: "/editproducts/:id",
    name: "posts.edit",
    component: () =>
      import(/* webpackChunkName: "edit" */ "../views/products/edit.vue"),
  },
  
];
//create router
const router = createRouter({
  history: createWebHistory(),
  routes, // <-- routes,
});
export default router;
