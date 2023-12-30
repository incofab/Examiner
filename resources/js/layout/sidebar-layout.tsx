import React from 'react';
import {
  Sidebar,
  Menu,
  MenuItem,
  sidebarClasses,
  menuClasses,
  SubMenu,
  MenuItemStyles,
} from 'react-pro-sidebar';
import { SidebarHeader } from '../components/sidebar-header';
import { InertiaLink } from '@inertiajs/inertia-react';
import route from '@/util/route';
import { Nullable, InstitutionUserType } from '@/types/types';
import useSharedProps from '@/hooks/use-shared-props';

interface MenuType {
  label: string;
  icon?: string;
  roles?: Nullable<InstitutionUserType[]>;
  route?: string;
}

interface MenuListType extends MenuType {
  sub_items?: MenuType[];
}

export default function SideBarLayout() {
  const { currentUser } = useSharedProps();
  const staffOnly = [InstitutionUserType.Admin, InstitutionUserType.Teacher];

  const menus: MenuListType[] = [
    {
      label: 'Dashboard',
      route: route('dashboard'),
    },
    {
      label: 'My Results',
      route: route('students.term-results.index'),
      roles: [InstitutionUserType.Student],
    },
    {
      label: 'Staff',
      roles: [InstitutionUserType.Admin],
      sub_items: [
        {
          label: 'All Staff',
          route: route('users.index', { staffOnly: true }),
          roles: [InstitutionUserType.Admin, InstitutionUserType.Teacher],
        },
        {
          label: 'Add Staff',
          route: route('users.create'),
          roles: [InstitutionUserType.Admin],
        },
      ],
    },
    {
      label: 'Students',
      roles: staffOnly,
      sub_items: [
        {
          label: 'All Students',
          route: route('students.index'),
          roles: staffOnly,
        },
        {
          label: 'Add Student',
          route: route('students.create'),
          roles: [InstitutionUserType.Admin],
        },
      ],
    },
    {
      label: 'Subject',
      sub_items: [
        {
          label: 'All Subject',
          route: route('courses.index'),
          roles: [
            InstitutionUserType.Student,
            InstitutionUserType.Admin,
            InstitutionUserType.Teacher,
          ],
        },
        {
          label: 'Add Subject',
          route: route('courses.create'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'Subject Teachers',
          route: route('course-teachers.index'),
          roles: [InstitutionUserType.Admin, InstitutionUserType.Teacher],
        },
        {
          label: 'Recorded Results',
          route: route('course-result-info.index'),
          roles: staffOnly,
        },
      ],
    },
    {
      label: 'Classes',
      roles: staffOnly,
      sub_items: [
        {
          label: 'All Classes',
          route: route('classifications.index'),
          roles: staffOnly,
        },
        {
          label: 'Add Class',
          route: route('classifications.create'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'All Class Groups',
          route: route('classification-groups.index'),
          roles: staffOnly,
        },
        {
          label: 'Student Class Changes',
          route: route('student-class-movements.index'),
          roles: [InstitutionUserType.Admin, InstitutionUserType.Teacher],
        },
        {
          label: 'Class Result',
          route: route('class-result-info.index'),
          roles: [InstitutionUserType.Admin, InstitutionUserType.Teacher],
        },
        {
          label: 'Session Result',
          route: route('session-results.index'),
          roles: [InstitutionUserType.Admin, InstitutionUserType.Teacher],
        },
      ],
    },
    {
      label: 'Admin',
      roles: [InstitutionUserType.Admin],
      sub_items: [
        {
          label: 'School Profile',
          route: route('profile'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'Pins',
          route: route('pin-prints.index'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'Assessments',
          route: route('assessments.index'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'Cummulative Results',
          route: route('cummulative-result.index'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'Result Comments',
          route: route('result-comment-templates.index'),
          roles: [InstitutionUserType.Admin],
        },
      ],
    },
    {
      label: 'Payments',
      roles: [InstitutionUserType.Admin],
      sub_items: [
        {
          label: 'List Fee Types',
          route: route('fees.index'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'Add Fee Type',
          route: route('fees.create'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'List Fee Payments',
          route: route('fee-payments.index'),
          roles: [InstitutionUserType.Admin],
        },
      ],
    },
    {
      label: 'Events',
      route: route('events.index'),
      roles: [InstitutionUserType.Admin],
    },
    {
      label: 'Settings',
      route: route('settings.create'),
      roles: [InstitutionUserType.Admin],
    },
    {
      label: 'Evaluations',
      roles: [InstitutionUserType.Admin],
      sub_items: [
        {
          label: 'Evaluation Types',
          route: route('learning-evaluation-domains.index'),
          roles: [InstitutionUserType.Admin],
        },
        {
          label: 'Evaluations',
          route: route('learning-evaluations.index'),
          roles: [InstitutionUserType.Admin],
        },
      ],
    },
    {
      label: 'Profile',
      route: route('users.profile', [currentUser]),
    },
    {
      label: 'Logout',
      route: route('logout'),
    },
  ];

  const menuItemStyles: MenuItemStyles = {
    root: {
      fontSize: '14px',
      fontWeight: 500,
    },
    // icon: {
    //   color: themes[theme].menu.icon,
    //   [`&.${menuClasses.disabled}`]: {
    //     color: themes[theme].menu.disabled.color,
    //   },
    // },
    SubMenuExpandIcon: {
      color: 'purple',
    },
    subMenuContent: ({ level }) => ({
      backgroundColor: level === 0 ? '#123a2b' : 'transparent',
    }),
    button: {
      color: '#c2c1c1',
      [`&.${menuClasses.disabled}`]: {
        color: 'gray',
      },
      '&:hover': {
        backgroundColor: '#2a8864',
        color: '#ffffff',
      },
    },
    label: ({ open }) => ({
      fontWeight: open ? 800 : undefined,
    }),
  };

  return (
    <Sidebar
      breakPoint="lg"
      rootStyles={{
        [`.${sidebarClasses.container}`]: {
          backgroundColor: '#06130e',
        },
      }}
    >
      <SidebarHeader />
      <Menu menuItemStyles={menuItemStyles}>
        {menus.map(function (menu: MenuListType, i: number) {
          if (menu.roles) {
            return;
          }
          if (!menu.sub_items) {
            return (
              <MenuItem
                key={i}
                component={<InertiaLink href={menu.route ?? ''} />}
              >
                {menu.label}
              </MenuItem>
            );
          }
          return (
            <SubMenu label={menu.label} key={i}>
              {menu.sub_items.map(function (subItem: MenuListType, i: number) {
                if (subItem.roles) {
                  return;
                }
                return (
                  <MenuItem
                    key={'j' + i}
                    component={<InertiaLink href={subItem.route ?? ''} />}
                  >
                    {subItem.label}
                  </MenuItem>
                );
              })}
            </SubMenu>
          );
        })}
      </Menu>
    </Sidebar>
  );
}
