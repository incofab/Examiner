import React from 'react';
import { Exam, User } from '@/types/models';
import { Divider, HStack, Icon, Text, VStack } from '@chakra-ui/react';
import ExamLayout from '../exam-layout';
import { AcademicCapIcon } from '@heroicons/react/24/solid';
import { LabelText } from '@/components/result-helper-components';
import { Div } from '@/components/semantic';
import { LinkButton } from '@/components/buttons';
import CenteredBox from '@/components/centered-box';

interface Props {
  exam: Exam;
  user: User;
}

export default function ExamResult({ exam, user }: Props) {
  const displayData = [
    { label: 'Exam No', value: exam.exam_no },
    { label: 'Num of Subjects', value: exam.exam_items?.length },
    { label: 'Num of Questions', value: exam.num_of_questions },
    { label: 'Total Score', value: exam.score },
  ];

  return (
    <ExamLayout
      title={'Exam Result'}
      user={user}
      exam={exam}
      rightElement={
        <Div>
          <Text fontWeight={'bold'} color={'brand.100'}>
            Congratulations
          </Text>
          <Text>{`${user?.full_name}`}</Text>
        </Div>
      }
      breadCrumbItems={[
        {
          title: 'Exam Result',
        },
      ]}
    >
      <CenteredBox>
        <VStack align={'stretch'} spacing={3}>
          <HStack justify={'space-between'} px={3}>
            <Icon as={AcademicCapIcon} fontSize={'7xl'} color={'brand.700'} />
            <Div
              borderRadius={'50%'}
              p={3}
              background={'brand.500'}
              color={'white'}
            >
              <Text
                fontWeight={'bold'}
                fontSize={'3xl'}
              >{`${exam.score}/${exam.num_of_questions}`}</Text>
            </Div>
          </HStack>
          <br />
          <VStack align={'stretch'} spacing={3}>
            {displayData.map(({ label, value }) => (
              <LabelText
                key={label}
                label={label}
                text={value}
                labelProps={{ width: '150px' }}
              />
            ))}
          </VStack>
          <br />
          <VStack align={'stretch'} spacing={3} divider={<Divider />}>
            <HStack
              align={'stretch'}
              justify={'space-between'}
              fontWeight={'bold'}
            >
              <Text flex={1}>Subject(s)</Text>
              <Text flex={1}>Num of Questions</Text>
              <Text flex={1}>Score</Text>
            </HStack>
            {exam.exam_items?.map((examItem) => {
              return (
                <HStack
                  align={'stretch'}
                  justify={'space-between'}
                  key={examItem.id}
                >
                  <Text flex={1}>{examItem.course_session?.course?.title}</Text>
                  <Text flex={1}>{examItem.num_of_questions}</Text>
                  <Text flex={1}>{examItem.score}</Text>
                </HStack>
              );
            })}
          </VStack>
          <br />
          <LinkButton title="Home" href={''} />
        </VStack>
      </CenteredBox>
    </ExamLayout>
  );
}
