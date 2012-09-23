## Gear Events

For those of you that use Laravel, Feather makes use of the Events system to power Gear events. Events are fired at points throughout the execution of Feather and your own custom events can be fired.

### Event Naming Convention

Feather uses a very simple naming convention for all events that are fired. This is an event in its most basic form.

    {category}: {timing} {event}

An example of this convention is as follows.

    validation: before auth.register

The above event is fired before the registrations validation as assessed. The above event is useful when you want your Gear to add custom inputs to the registration form.

### Asset Events

<!-- assets: change styles -->
<table class="api">
    <tr>
        <th colspan="2"><h4>assets: change styles</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Receives</td>
        <td>
            <table class="parameters">
                <tr>
                    <th>Parameter</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td class="parameter" markdown="1">`object $container`</td>
                    <td>Asset container being used by current theme.</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `void`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2">
            Fired after the controllers method has been run. An asset container for the theme is passed to the event allowing styles within the container to be modified.
        </td>
    </tr>
</table>

<!-- assets: change scripts -->
<table class="api">
    <tr>
        <th colspan="2"><h4>assets: change scripts</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Receives</td>
        <td>
            <table class="parameters">
                <tr>
                    <th>Parameter</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td class="parameter" markdown="1">`object $container`</td>
                    <td>Asset container being used by current theme.</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `void`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2">
            Fired after the controllers method has been run. An asset container for the theme is passed to the event allowing scripts within the container to be modified.
        </td>
    </tr>
</table>

### Controller Events

<!-- controller: before {controller.name}@{verb}.{method.name} -->
<table class="api">
    <tr>
        <th colspan="2"><h4>controller: before {controller.name}@{verb}.{method.name}</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Receives</td>
        <td>
            <table class="parameters">
                <tr>
                    <th>Parameter</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td class="parameter" markdown="1">`object $controller`</td>
                    <td>Instance of the controller object being resolved.</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `void`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2">
            Fired before a given method on a given controller is executed. Listeners receive an instance of the controller object.
        </td>
    </tr>
</table>

<!-- controller: after {controller.name}@{verb}.{method.name} -->
<table class="api">
    <tr>
        <th colspan="2"><h4>controller: after {controller.name}@{verb}.{method.name}</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Receives</td>
        <td>
            <table class="parameters">
                <tr>
                    <th>Parameter</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td class="parameter" markdown="1">`object $controller`</td>
                    <td>Instance of the controller object being resolved.</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `void`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2">
            Fired after a given method on a given controller is executed. Listeners receive an instance of the controller object.
        </td>
    </tr>
</table>

<!-- controller: override {controller.name}@{verb}.{method.name} -->
<table class="api">
    <tr>
        <th colspan="2"><h4>controller: override {controller.name}@{verb}.{method.name}</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Receives</td>
        <td>
            <table class="parameters">
                <tr>
                    <th>Parameter</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td class="parameter" markdown="1">`object $controller`</td>
                    <td>Instance of the controller object being resolved.</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Overrides a given method on a given controller. No response needs to be returned meaning the event can interact with the `layout` property on the controller instance.
        </td>
    </tr>
</table>

<!-- controller: create {controller.name}@{verb}.{method.name} -->
<table class="api">
    <tr>
        <th colspan="2"><h4>controller: create {controller.name}@{verb}.{method.name}</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Receives</td>
        <td>
            <table class="parameters">
                <tr>
                    <th>Parameter</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td class="parameter" markdown="1">`object $controller`</td>
                    <td>Instance of the controller object being resolved.</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Create a method for the given HTTP verb on a given controller. As with overriding a method you can interact with the `layout` property on the controller instance.
        </td>
    </tr>
</table>

<div class="alert alert-info" markdown="1">
**Don't forget!**

All controller events receive the controller instance meaning you can interact with the all methods and properties on that controller.
</div>

### View Events

<!-- view: before category.title -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before category.title</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the category title. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after category.title -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after category.title</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the category title. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: before category.discussion.counter -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before category.discussion.counter</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the category discussion counter. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after category.discussion.counter -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after category.discussion.counter</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the category discussion counter. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: before category.discussion.title -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before category.discussion.title</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the category discussion title. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after category.discussion.title -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after category.discussion.title</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the category discussion title. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: before category.discussion.meta -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before category.discussion.meta</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the category discussion meta information. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after category.discussion.meta -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after category.discussion.meta</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the category discussion meta information. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: before category.discussion.stats -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before category.discussion.stats</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the category discussion statistics. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after category.discussion.stats -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after category.discussion.stats</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the category discussion statistics. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: before template.title -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before template.title</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the template title. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after template.title -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after template.title</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the template title. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: before template.meta -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before template.meta</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the template meta. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after template.meta -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after template.meta</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the template meta. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: before register.rules -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: before register.rules</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired before the registration rules. Expects a value to be returned.
        </td>
    </tr>
</table>

<!-- view: after register.rules -->
<table class="api">
    <tr>
        <th colspan="2"><h4>view: after register.rules</h4></th>
    </tr>
    <tr>
        <td class="api-label" width="15%">Expects</td>
        <td class="parameter" markdown="1">
            `mixed`
        </td>
    </tr>
    <tr>
        <td class="description" colspan="2" markdown="1">
            Fired after the registration rules. Expects a value to be returned.
        </td>
    </tr>
</table>