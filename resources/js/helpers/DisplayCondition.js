import {each, get, includes, map} from "lodash";

class DisplayCondition {
    getFailureMessage(displayConditions, instance) {
        let displayConditionMessages = '';

        // If we are dealing with an array, the first instance
        // of all checks passing means we pass the checks
        if (displayConditions.constructor === Array) {
            each(displayConditions, (condition) => {
                if (this.check(condition, instance)) {
                    displayConditionMessages += condition.message + ' ';
                    console.log('failed', condition.message)
                }
            });

        } else {
            if (this.check(displayConditions, instance)) {
                displayConditionMessages += displayConditions.message;
                console.log('failed', displayConditions.message)
            }
        }

        return displayConditionMessages ? displayConditionMessages : false;
    }

    passes(displayConditions, instance) {
        // If there is no display condition we just skip everything
        if (!displayConditions) { return true; }

        let displayConditionsPassed = false;
        let resolvedDisplayConditions = [];

        // If we are dealing with an array, the first instance
        // of all checks passing means we pass the checks
        if (displayConditions.constructor === Array) {
            each(displayConditions, (condition) => {
                if (displayConditionsPassed) { return; }

                resolvedDisplayConditions = this.check(condition, instance);

                if (!includes(resolvedDisplayConditions, false)) {
                    displayConditionsPassed = true;
                }
            });

        } else {
            resolvedDisplayConditions = this.check(displayConditions, instance);
        }

        return displayConditionsPassed || !includes(resolvedDisplayConditions, false);
    }

    check(displayCondition, instance) {
        return map(displayCondition.rules, (value, field) => {
            if (field === 'PERMISSION') {
                return User.can(value);
            }

            if (field === 'ROLE') {
                return User.is(value);
            }

            if (value === '0-LENGTH') {
                return get(instance, field, []).length === 0;
            }

            if (value.constructor === Boolean) {
                return !!get(instance, field) === value;
            }

            if (value.constructor === String || value.constructor === Number) {
                return get(instance, field) === value;
            }

            if (value.constructor === Array) {
                return value.includes(get(instance, field));
            }

            return false;
        });
    };
}

export default new DisplayCondition();
