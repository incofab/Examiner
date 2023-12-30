import React, { useEffect, useMemo, useState } from 'react';
import { Exam, ExamItem, Question, User } from '@/types/models';
import {
  Center,
  HStack,
  Icon,
  IconButton,
  Radio,
  Spacer,
  Tab,
  TabList,
  TabPanel,
  TabPanels,
  Tabs,
  Text,
  VStack,
  Wrap,
  WrapItem,
} from '@chakra-ui/react';
import ExamUtil from '@/util/exam/exam-util';
import ExamLayout from '../exam-layout';
import useModalToggle from '@/hooks/use-modal-toggle';
import CalculatorModal from '@/components/modals/calculator-modal';
import { CalculatorIcon } from '@heroicons/react/24/solid';
import { BrandButton } from '@/components/buttons';
import ExamTimer from '@/util/exam/exam-timer';
import { formatTime } from '@/util/util';
import { Inertia } from '@inertiajs/inertia';
import useWebForm from '@/hooks/use-web-form';
import { ExamAttempt } from '@/types/types';
import QuestionImageHandler from '@/util/exam/question-image-handler';
import route from '@/util/route';

interface Props {
  exam: Exam;
  user?: User;
  timeRemaining: number;
  existingAttempts: ExamAttempt;
}

export default function DisplayExam({
  exam,
  user,
  timeRemaining,
  existingAttempts,
}: Props) {
  const [key, setKey] = useState<string>('0');
  const webForm = useWebForm({});
  const calculatorModalToggle = useModalToggle();

  function updateExamUtil() {
    setKey(Math.random() + '');
  }

  const examUtil = useMemo(() => {
    const examUtil = new ExamUtil(exam, existingAttempts, updateExamUtil);
    return examUtil;
  }, []);

  async function onTimeElapsed() {
    await examUtil.getAttemptManager().sendAttempts(webForm);
    gotoResultPage();
  }

  function onIntervalPing() {
    examUtil.getAttemptManager().sendAttempts(webForm);
  }

  async function submitExam() {
    if (!confirm('Do you want to submit your exam?')) {
      return;
    }
    await webForm.submit((data, web) => {
      return web.post(route('end-exam', [exam.id]));
    });
    gotoResultPage();
  }
  function gotoResultPage() {
    Inertia.visit(
      exam.show_result
        ? route('exam-result', [exam.exam_no])
        : route('exams.completed-message')
    );
  }

  function previousClicked() {
    examUtil
      .getTabManager()
      .setCurrentQuestion(examUtil.getExamNavManager().getGoPreviousIndex());
  }

  function nextClicked() {
    examUtil
      .getTabManager()
      .setCurrentQuestion(examUtil.getExamNavManager().getGoNextIndex());
  }

  const handleKeyDown = (event: React.KeyboardEvent<HTMLDivElement>) => {
    const pressedKey = event.key.toUpperCase() ?? '';
    // console.log('Key pressed:', event.key);
    switch (pressedKey) {
      case 'A':
      case 'B':
      case 'C':
      case 'D':
        // case 'E':
        examUtil
          .getAttemptManager()
          .setAttempt(
            examUtil.getTabManager().getCurrentQuestion()?.id,
            pressedKey
          );
        break;
      case 'N':
        nextClicked();
        break;
      case 'P':
        previousClicked();
        break;
      case 'S':
        submitExam();
        break;
      case 'R':
        break;
    }
  };

  return (
    <ExamLayout
      title={''}
      exam={exam}
      user={user}
      breadCrumbItems={[{ title: `${user?.full_name}`, href: '#' }]}
      rightElement={
        <HStack>
          <IconButton
            icon={<Icon as={CalculatorIcon} />}
            aria-label="Calculator"
            onClick={calculatorModalToggle.open}
            variant={'ghost'}
            fontSize={'2xl'}
          />
          <TimerView
            timeRemaining={timeRemaining}
            onTimeElapsed={onTimeElapsed}
            onIntervalPing={onIntervalPing}
          />
        </HStack>
      }
      onKeyDown={handleKeyDown}
    >
      <Tabs
        key={key}
        index={examUtil.getTabManager().getCurrentTabIndex()}
        mx={{ base: '5px', md: '30px' }}
      >
        <TabList overflowX={'auto'}>
          {exam.exam_items?.map((item, index) => (
            <Tab
              key={item.id}
              onClick={() => examUtil.getTabManager().setCurrentTabIndex(index)}
            >
              {item.course_session?.course?.title}
            </Tab>
          ))}
        </TabList>
        <TabPanels>
          {exam.exam_items?.map((examItem, index) => {
            const tab = examUtil.getTabManager().getTab(index);
            examUtil.getTabManager().setTab(index, {
              currentQuestionIndex: tab?.currentQuestionIndex ?? 0,
              exam_item_id: examItem.id,
            });
            return (
              <TabPanel key={examItem.id}>
                <DisplayQuestion examItem={examItem} examUtil={examUtil} />
              </TabPanel>
            );
          })}
        </TabPanels>
      </Tabs>
      <CalculatorModal {...calculatorModalToggle.props} />
      <HStack
        justifyContent={'space-between'}
        px={3}
        py={2}
        mt={2}
        position={'absolute'}
        bottom={0}
        w={'full'}
      >
        <BrandButton
          title="Previous"
          onClick={previousClicked}
          width={'80px'}
        />
        <BrandButton title="Submit" onClick={submitExam} width={'80px'} />
        <BrandButton title="Next" onClick={nextClicked} width={'80px'} />
      </HStack>
    </ExamLayout>
  );
}

function TimerView({
  timeRemaining,
  onTimeElapsed,
  onIntervalPing,
}: {
  timeRemaining: number;
  onTimeElapsed: () => void;
  onIntervalPing: () => void;
}) {
  const [timer, setTimer] = useState<string>('');

  useEffect(() => {
    const examTimer = new ExamTimer(onTimerTick, onTimeElapsed, onIntervalPing);
    examTimer.start(timeRemaining);
    return () => examTimer.stop();
  }, []);

  function onTimerTick(timeRemaining: number) {
    setTimer(formatTime(timeRemaining) + '');
  }
  return <Text>{timer}</Text>;
}

function DisplayQuestion({
  examItem,
  examUtil,
}: {
  examItem: ExamItem;
  examUtil: ExamUtil;
}) {
  const attemptManager = examUtil.getAttemptManager();
  const questions = examItem.course_session!.questions!;

  const questionIndex = examUtil.getTabManager().getCurrentQuestionIndex();
  const question = questions[questionIndex];
  const questionImageHandler = new QuestionImageHandler(
    examItem.course_session!
  );
  if (!question) {
    return null;
  }
  return (
    <VStack align={'stretch'}>
      <Text fontWeight={'bold'}>
        Question {questionIndex + 1} of {questions.length}
      </Text>
      <Text
        my={2}
        dangerouslySetInnerHTML={{
          __html: questionImageHandler.handleImages(question.question),
        }}
      />
      <VStack align={'stretch'} spacing={1}>
        {[
          {
            optionText: questionImageHandler.handleImages(question.option_a),
            optionLetter: 'A',
          },
          {
            optionText: questionImageHandler.handleImages(question.option_b),
            optionLetter: 'B',
          },
          {
            optionText: questionImageHandler.handleImages(question.option_c),
            optionLetter: 'C',
          },
          {
            optionText: questionImageHandler.handleImages(question.option_d),
            optionLetter: 'D',
          },
          {
            optionText: questionImageHandler.handleImages(question.option_e),
            optionLetter: 'E',
          },
        ].map((item) => (
          <DisplayOption
            key={item.optionLetter}
            optionText={item.optionText}
            optionLetter={item.optionLetter}
            examUtil={examUtil}
            question={question}
          />
        ))}
      </VStack>
      <Spacer />
      <Wrap>
        {questions.map((item, index) => (
          <WrapItem key={item.id}>
            <Center
              w="40px"
              h="35px"
              cursor={'pointer'}
              border={'solid 1px'}
              borderColor={'gray.600'}
              rounded={'md'}
              onClick={() => {
                examUtil.getTabManager().setCurrentQuestion(index);
              }}
              backgroundColor={
                item.id === question.id
                  ? 'brand.500'
                  : attemptManager.isAttempted(item.id)
                  ? 'brand.100'
                  : 'transparent'
              }
            >
              {index + 1}
            </Center>
          </WrapItem>
        ))}
      </Wrap>
    </VStack>
  );
}

function DisplayOption({
  optionText,
  optionLetter,
  examUtil,
  question,
}: {
  optionText: string;
  optionLetter: string;
  question: Question;
  examUtil: ExamUtil;
}) {
  if (!optionText) {
    return null;
  }
  return (
    <HStack align={'stretch'}>
      <Text fontWeight={'md'}>{optionLetter + ')'}</Text>
      <Radio
        isChecked={
          examUtil.getAttemptManager().getAttempt(question.id) === optionLetter
        }
        onChange={(e) => {
          examUtil.getAttemptManager().setAttempt(question.id, optionLetter);
        }}
      >
        <Text dangerouslySetInnerHTML={{ __html: optionText }} />
      </Radio>
    </HStack>
  );
}
