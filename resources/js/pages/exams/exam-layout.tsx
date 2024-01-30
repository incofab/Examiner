import {
  Flex,
  Avatar,
  HStack,
  Text,
  IconButton,
  Button,
  useDisclosure,
  Stack,
  Icon,
  VStack,
  Divider,
  BoxProps,
} from '@chakra-ui/react';
import { Bars3Icon, XMarkIcon } from '@heroicons/react/24/solid';
import { Div } from '@/components/semantic';
import { BreadCrumbParam } from '@/types/types';
import { InertiaLink } from '@inertiajs/inertia-react';
import { PropsWithChildren, ReactNode } from 'react';
import ShowBreadCrumb from './component/show-bread-crumb';
import { avatarUrl } from '@/util/util';
import { Exam, User } from '@/types/models';

interface Props {
  title: string | ReactNode;
  rightElement?: string | ReactNode;
  breadCrumbItems?: BreadCrumbParam[];
  user?: User;
  exam: Exam;
}

const NavLink = ({ link }: { link: BreadCrumbParam }) => {
  return (
    <Button
      as={InertiaLink}
      rounded={'md'}
      _hover={{
        textDecoration: 'underline',
        color: 'brand.300',
        cursor: 'pointer',
      }}
      href={link.href}
      variant={'link'}
      color={'brand.50'}
    >
      {link.title}
    </Button>
  );
};

export default function ExamLayout({
  title,
  rightElement,
  children,
  breadCrumbItems,
  user,
  exam,
  ...props
}: Props & BoxProps & PropsWithChildren) {
  const { isOpen, onOpen, onClose } = useDisclosure();

  const links: { title: string; href: string }[] = [];

  return (
    <Div background={'brand.50'} minH={'100vh'} {...props}>
      <Div
        background={'brand.700'}
        color={'white'}
        shadow={'md'}
        py={'15px'}
        px={'20px'}
      >
        <Flex h={16} alignItems={'center'} justifyContent={'space-between'}>
          <IconButton
            size={'md'}
            icon={<Icon as={isOpen ? XMarkIcon : Bars3Icon} fontSize={'2xl'} />}
            aria-label={'Open Menu'}
            display={{ md: 'none' }}
            onClick={isOpen ? onClose : onOpen}
            variant={'outline'}
            colorScheme={'whiteAlpha'}
          />
          <VStack spacing={1} alignItems={'center'}>
            <Div>
              <Text fontWeight={'bold'} fontSize={'18px'} color={'brand.100'}>
                {exam.platform}
              </Text>
            </Div>
            <HStack
              as={'nav'}
              spacing={4}
              display={{ base: 'none', md: 'flex' }}
            >
              {links.map((link) => (
                <NavLink key={link.title} link={link} />
              ))}
            </HStack>
          </VStack>
          <HStack spacing={2}>
            {user && <Avatar size={'sm'} src={avatarUrl(user?.full_name)} />}
            <Div>{rightElement}</Div>
          </HStack>
        </Flex>

        {isOpen ? (
          <Div pb={4} display={{ md: 'none' }}>
            <Stack as={'nav'} spacing={4} alignItems={'start'}>
              {links.map((link) => (
                <NavLink key={link.title} link={link} />
              ))}
            </Stack>
          </Div>
        ) : null}
      </Div>

      <ShowBreadCrumb breadCrumbItems={links} />
      <Div px={'8px'}>
        {title && (
          <>
            <Text
              fontWeight={'bold'}
              fontSize={'3xl'}
              color={'brand.600'}
              textAlign={'center'}
              mt={3}
            >
              {title}
            </Text>
            <Divider my={2} />{' '}
          </>
        )}
        <Div py={'20px'}>{children}</Div>
      </Div>
    </Div>
  );
}
