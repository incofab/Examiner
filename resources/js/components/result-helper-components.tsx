import React from 'react';
import { Text, TextProps } from '@chakra-ui/react';
import { Div } from './semantic';

export const LabelText = function ({
  label,
  text,
  labelProps,
  textProps,
}: {
  label: string;
  text: string | number | undefined | React.ReactNode;
  labelProps?: TextProps;
  textProps?: TextProps;
}) {
  return (
    <Div>
      <Text
        as={'span'}
        fontWeight={'semibold'}
        display={'inline-block'}
        {...labelProps}
      >
        {label}:
      </Text>
      <Text as={'span'} ml={3} {...textProps} display={'inline-block'}>
        {text}
      </Text>
    </Div>
  );
};
