import {each, get, has, includes, map} from "lodash";

class DisplayCondition {
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
        return map(displayCondition, (value, field) => {
            if (field === 'PERMISSION') {
                return User.can(value);
            }

            if (field === 'ROLE') {
                return User.is(value);
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