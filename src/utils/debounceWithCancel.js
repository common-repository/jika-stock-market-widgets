import { useCallback, useState } from 'react';

const useDebounceWithCancel = (func, timeout = 300) => {
	const [timer, setTimer] = useState();
	const debouncedFunc = useCallback(
		(...args) => {
			clearTimeout(timer);
			setTimer(
				setTimeout(() => {
					func.apply(this, args);
				}, timeout)
			);
		},
		[timer, func, timeout]
	);
	const cancelDebounce = useCallback(() => {
		clearTimeout(timer);
	}, [timer]);
	return [debouncedFunc, cancelDebounce];
};

export default useDebounceWithCancel;
