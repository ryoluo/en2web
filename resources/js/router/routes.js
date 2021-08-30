import Login from '@/js/pages/login/index.vue';
import Notes from '@/js/pages/notes/index.vue';
import NoteView from '@/js/pages/notes/view.vue';
import NoteForm from '@/js/pages/notes/form.vue';
import NoteDelete from '@/js/pages/notes/delete.vue';
// import NoteUsers from '@/js/pages/notes/users.vue';
import NoteSearch from '@/js/pages/notes/search.vue';
import Users from '@/js/pages/users/index.vue';
import UserView from '@/js/pages/users/view.vue';
import Settings from '@/js/pages/settings/index.vue';
import SettingProfile from '@/js/pages/settings/profile.vue';
import SettingIcon from '@/js/pages/settings/icon.vue';
import SettingCover from '@/js/pages/settings/cover.vue';
import RegisterVefiry from '@/js/pages/register/verify.vue';
import PasswordRemind from '@/js/pages/password/reset/index.vue';
import PasswordReset from '@/js/pages/password/reset/form.vue';

const noteActions = [
  { icon: 'mdi-magnify', to: '/notes/search' },
  { icon: 'mdi-plus', to: '/notes/create' },
];

const routes = [
  {
    path: '/register/verify/:token',
    component: RegisterVefiry,
    name: 'register.verify',
    meta: {
      requireAuth: false,
      title: 'En2::Web',
      header: 'menu',
      actions: [],
    },
  },
  {
    path: '/login',
    component: Login,
    name: 'login',
    meta: {
      requireAuth: false,
      title: 'Login',
      header: 'menu',
      actions: [],
    },
  },
  {
    path: '/password/reset',
    component: PasswordRemind,
    name: 'password.remind',
    meta: {
      requireAuth: false,
      title: 'Password Reset',
      header: 'menu',
      actions: [],
    },
  },
  {
    path: '/password/reset/:token',
    component: PasswordReset,
    name: 'password.reset',
    meta: {
      requireAuth: false,
      title: 'Password Reset',
      header: 'menu',
      actions: [],
    },
  },
  // redirect only
  {
    path: '/',
    component: Login,
    name: 'home',
    meta: {
      requireAuth: true,
      title: 'Login',
      header: 'menu',
      actions: [],
    },
  },
  {
    path: '/mypage',
    component: UserView,
    name: 'mypage',
    meta: {
      requireAuth: true,
      title: 'Mypage',
      header: 'menu',
      actions: [
        {
          icon: 'mdi-account-cog',
          to: '/settings',
        },
      ],
    },
  },
  {
    path: '/settings',
    component: Settings,
    name: 'setting',
    meta: {
      requireAuth: true,
      title: 'Setting',
      header: 'back',
      actions: [],
    },
  },
  {
    path: '/settings/profile',
    component: SettingProfile,
    name: 'setting.profile',
    meta: {
      requireAuth: true,
      title: 'Setting',
      header: 'back',
      actions: [],
    },
  },
  {
    path: '/settings/icon',
    component: SettingIcon,
    name: 'setting.icon',
    meta: {
      requireAuth: true,
      title: 'Setting',
      header: 'back',
      actions: [],
    },
  },
  {
    path: '/settings/cover',
    component: SettingCover,
    meta: {
      requireAuth: true,
      title: 'Setting',
      header: 'back',
      actions: [],
    },
  },
  {
    path: '/notes',
    component: Notes,
    name: 'note.index',
    meta: {
      requireAuth: true,
      title: 'Notes',
      header: 'menu',
      actions: noteActions,
    },
  },
  {
    path: '/notes/create',
    component: NoteForm,
    name: 'note.create',
    meta: {
      requireAuth: true,
      title: 'Note',
      header: 'back',
      actions: [],
    },
  },
  {
    path: '/notes/:id(\\d+)',
    component: NoteView,
    name: 'note.view',
    meta: {
      requireAuth: true,
      title: 'Notes',
      header: 'back',
      actions: noteActions,
    },
  },
  {
    path: '/notes/:id(\\d+)/edit',
    component: NoteForm,
    name: 'note.edit',
    meta: {
      requireAuth: true,
      title: 'Note',
      header: 'back',
      actions: [],
    },
  },
  {
    path: '/notes/:id(\\d+)/delete',
    component: NoteDelete,
    name: 'note.delete',
    meta: {
      requireAuth: true,
      title: 'Note',
      header: 'back',
      actions: [],
    },
  },
  // {
  //   path: '/notes/:id(\\d+)/users',
  //   component: NoteUsers,
  //   name: 'note.user',
  //   meta: {
  //     requireAuth: true,
  //     title: 'Liked Users',
  //     header: 'back',
  //     actions: noteActions,
  //   },
  // },
  {
    path: '/notes/search',
    component: NoteSearch,
    name: 'note.search',
    meta: {
      requireAuth: true,
      title: 'Notes',
      header: 'back',
      actions: [],
    },
  },
  {
    path: '/users',
    component: Users,
    name: 'user.index',
    meta: {
      requireAuth: true,
      title: 'Users',
      header: 'menu',
      actions: [],
    },
  },
  {
    path: '/users/:id(\\d+)',
    component: UserView,
    name: 'user.view',
    meta: {
      requireAuth: true,
      title: 'Users',
      header: 'menu',
      actions: [],
    },
  },
];

export default routes;
