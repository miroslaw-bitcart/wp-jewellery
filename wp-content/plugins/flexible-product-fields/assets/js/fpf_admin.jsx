let Fields = null;
let Settings = null;
let Actions = null;

let getProductsOptions = (input) => {
    return fetch( fpf_admin.rest_url + 'flexible_product_fields/v1/products/?search=' + input + '&_wp_rest_nonce=' + fpf_admin.rest_nonce,
        ).then((response) => {
            return response.json();
        }).then((json) => {
            return { options: json };
        });
}

let getCategoriesOptions = (input) => {
    return fetch( fpf_admin.rest_url + 'flexible_product_fields/v1/categories/?search=' + input + '&_wp_rest_nonce=' + fpf_admin.rest_nonce,
    ).then((response) => {
        return response.json();
    }).then((json) => {
        return { options: json };
    });
}


let { SortableContainer, SortableElement, SortableHandle, arrayMove } = window.SortableHOC;

const DragHandle = SortableHandle(() => <span className="fpf-drag"><span className="dashicons dashicons-menu"></span></span>);


const SortableItemOptions = SortableElement (
    ( {value, i, onRemove} ) =>
            <FPF_Option
                value={value}
                i={i}
                onRemove={onRemove}
            />
);


const SortableListOptions = SortableContainer(({items,onRemove}) => {
    return (
        <tbody>
            {items.map((value, index) =>
                <SortableItemOptions
                    key={`item-${value.id}`}
                    index={index}
                    i={index}
                    value={value}
                    onRemove={onRemove}
                />
            )}
        </tbody>
    );
});



const SortableItem = SortableElement (
    ( {value, i, onRemove} ) =>
        <FPF_Field
            i={i}
            data={value}
            onRemove={onRemove}
        />
);

const SortableList = SortableContainer(({items,onRemove}) => {
    return (
        <div>
            {items.map((value, index) =>
                <SortableItem
                    key={`item-${value.id}`}
                    index={index}
                    i={index}
                    value={value}
                    onRemove={onRemove}
                />
            )}
        </div>
    );
});

class SortableComponent extends React.Component {

    constructor( props ) {
        super( props );
        this.state = {
            items: props.items
        };
        this.onRemove = this.onRemove.bind(this);
    }

    onRemove( i ) {
        let items = this.state.items;
        for ( var n = 0 ; n < items.length ; n++) {
            if ( items[n].id == i ) {
                var removedObject = items.splice(n,1);
                removedObject = null;
                break;
            }
        }
        this.setState({items: items});
        Fields.setState( { fields: this.state.items } );
/*
        if ( typeof i != "undefined" ) {
            let items = this.state.items;
            items.splice(i, 1);
            this.setState({items: items});
            Fields.setState( { fields: this.state.items } );
        }
*/
    }

    onSortEnd = ( {oldIndex, newIndex} ) => {
        this.setState({
            items: arrayMove( this.state.items, oldIndex, newIndex )
        });
        //console.log( this.state.items );
        Fields.setState( { fields: this.state.items } );
        fpf_settings.fields = this.state.items;
    };

    render() {
        return (
            <SortableList
                items={this.state.items}
                onSortEnd={this.onSortEnd}
                useDragHandle={true}
                onRemove={this.onRemove}
            />
        )
    }
}


class FPF_Option extends React.Component {

    constructor(props) {
        super( props );
        this.state = {
            data: props.value,
        };

        this.onChangeValue = this.onChangeValue.bind(this);
        this.onChangeLabel = this.onChangeLabel.bind(this);
        this.onChangePriceType = this.onChangePriceType.bind(this);
        this.onChangePrice = this.onChangePrice.bind(this);
    }

    onChangeValue(event) {
        let data2 = this.state.data;
        data2.value = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangeLabel(event) {
        let data2 = this.state.data;
        data2.label = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangePriceType(event) {
        let data2 = this.state.data;
        data2.price_type = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangePriceType2(val) {
        let data2 = this.state.data;
        data2.price_type = val.value;
        this.setState( { data: data2 } );
    }

    onChangePrice(event) {
        let data2 = this.state.data;
        data2.price = event.target.value;
        this.setState( { data: data2 } );
    }

    render() {
        return (
            <tr>
                <td className="fpf-row-handle">
                    <DragHandle/>
                </td>
                <td>
                    <input type="text" className="fpf-field-option-value" name="option_value" value={this.state.data.value} onChange={this.onChangeValue}/>
                </td>
                <td>
                    <input type="text" className="fpf-field-option-label" name="option_label" value={this.state.data.label} onChange={this.onChangeLabel}/>
                </td>
                <td>
                    <select name="price_type" onChange={this.onChangePriceType} value={this.state.data.price_type}>
                        {
                            fpf_price_type_options.map(function (item) {
                                return <option key={item.value} value={item.value}>{item.label}</option>;
                            })
                        }
                    </select>
                </td>
                <td>
                    <input type="number" className="fpf-field-price" name="field_price" value={this.state.data.price} onChange={this.onChangePrice} step="0.01" />
                </td>
                <td className="fpf-row-handle">
                    <a className="fpf-option-delete dashicons dashicons-trash" onClick={() => this.props.onRemove(this.state.data.id)}> </a>
                </td>
            </tr>
        )
    }

}

class FPF_Field extends React.Component {

    constructor(props) {
        super( props );
        this.state = {
            data: props.data,
            i: props.i,
            key: props.key,
            other: props.other,
        };
        if ( typeof this.state.data.options == 'undefined' ) {
            this.state.data.options = [];
        }
        this.onChangeTitle = this.onChangeTitle.bind(this);
        this.onChangeType = this.onChangeType.bind(this);
        this.onChangeRequired = this.onChangeRequired.bind(this);
        this.onChangeCssClass = this.onChangeCssClass.bind(this);
        this.onChangePlaceholder = this.onChangePlaceholder.bind(this);
        this.onChangeValue = this.onChangeValue.bind(this);
        this.onChangePriceType = this.onChangePriceType.bind(this);
        this.onChangePrice = this.onChangePrice.bind(this);

        this.onClickToggleDisplay = this.onClickToggleDisplay.bind(this);
        this.handleMouseEnter = this.handleMouseEnter.bind(this);
        this.handleMouseLeave = this.handleMouseLeave.bind(this);

        this.handleAddOption = this.handleAddOption.bind(this);
        this.onRemoveOption = this.onRemoveOption.bind(this);

    }

    onChangeTitle(event) {
        let data2 = this.state.data;
        data2.title = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangeType(event) {
        let data2 = this.state.data;
        data2.type = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangeType2(val) {
        let data2 = this.state.data;
        data2.type = val.value;
        this.setState( { data: data2 } );
    }

    onChangeRequired(event) {
        let data2 = this.state.data;
        data2.required = !data2.required;
        this.setState( { data: data2 } );
    }

    onChangeCssClass(event) {
        let data2 = this.state.data;
        data2.css_class = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangePlaceholder(event) {
        let data2 = this.state.data;
        data2.placeholder = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangeValue(event) {
        let data2 = this.state.data;
        data2.value = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangePriceType(event) {
        let data2 = this.state.data;
        data2.price_type = event.target.value;
        this.setState( { data: data2 } );
    }

    onChangePriceType2(val) {
        let data2 = this.state.data;
        data2.price_type = val.value;
        this.setState( { data: data2 } );
    }

    onChangePrice(event) {
        let data2 = this.state.data;
        data2.price = event.target.value;
        this.setState( { data: data2 } );
    }

    onClickToggleDisplay(event) {
        let data2 = this.state.data;
        data2.display = !data2.display;
        this.setState( { data: data2 } );
    }

    handleMouseEnter(event) {
        this.setState( { mouseEnter: true } );
    }

    handleMouseLeave(event) {
        this.setState( { mouseEnter: false } );
    }

    handleAddOption(event) {

        event.preventDefault();

        let data = this.state.data;
        if ( typeof data.options == 'undefined' ) {
            data.options = [];
        }

        var key = "fpf_"+Math.floor((Math.random() * 10000000) + 1);
        var option = { id: key, value: '', label: '', price_type: fpf_price_type_options[0].value, price: '' };

        data.options.push( option );

        this.setState(
            { data: data }
        );
    }

    onSortEndOptions = ( {oldIndex, newIndex} ) => {
        let data = this.state.data;
        data.options = arrayMove( data.options, oldIndex, newIndex );
        this.setState({
            data: data
        });

    };

    onRemoveOption( i ) {
        let data = this.state.data;
        for ( var n = 0 ; n < data.options.length ; n++) {
            if ( data.options[n].id == i ) {
                var removedObject = data.options.splice(n,1);
                removedObject = null;
                break;
            }
        }
        this.setState({data: data});
    }

    render() {
        const showHide = {
            'display': this.state.data.display ? 'block' : 'none'
        };
        const required = this.state.data.required ? '*' : '';
        const toggleClass = this.state.data.display ? "open" : "closed";

        return (
            <div className={"fpf-field-object " + toggleClass}>
                <div className="fpf-field-title-row" onClick={this.onClickToggleDisplay} onMouseEnter={this.handleMouseEnter} onMouseLeave={this.handleMouseLeave}>
                    <div className="fpf-field-sort">
                        <DragHandle/>
                    </div>
                    <div className="fpf-field-title">
                        <strong>{this.state.data.title} {required}</strong>
                        { this.state.mouseEnter ?
                            <span className="fpf-row-actions">
                                <span className="fpf-edit-action">{fpf_admin.edit_label}</span>
                                &nbsp;|&nbsp;
                                <span className="fpf-delete-action" onClick={() => this.props.onRemove(this.state.data.id)}>{fpf_admin.delete_label}{this.state.index}</span>
                            </span>
                            :
                            <span className="fpf-row-actions">
                                &nbsp;
                            </span>
                        }
                    </div>
                    <div className="fpf-field-type">{fpf_field_types[this.state.data.type]['label']}</div>
                </div>
                <div className="fpf-field-inputs" style={showHide}>
                    <table className="fpf-table">
                        <tbody>
                            <tr className="fpf-field">
                                <td className="fpf-label">
                                    <label htmlFor={"field_title_" + this.state.id}>{fpf_admin.field_title_label}</label>
                                </td>

                                <td className="fpf-input">
                                    <input type="text" className="fpf-field-title" id={"field_title_" + this.state.id} name="field_title" value={this.state.data.title} onChange={this.onChangeTitle}/>
                                </td>
                            </tr>

                            <tr className="fpf-field">
                                <td className="fpf-label">
                                    <label htmlFor={"field_type_" + this.state.id}>{fpf_admin.field_type_label}</label>
                                </td>

                                <td className="fpf-input">
                                    <select id={"field_type_" + this.state.id} name="field_type" onChange={this.onChangeType} value={this.state.data.type}>
                                        {
                                            fpf_field_type_options.map(function (item) {
                                                return <option key={item.value} value={item.value}>{item.label}</option>;
                                            })
                                        }
                                    </select>
                                    {  fpf_field_types[this.state.data.type]['is_available'] ?
                                        null
                                        :
                                        <span>
                                            <br/><br/>
                                            {fpf_admin.fields_adv} <a href={fpf_admin.fields_adv_link}>{fpf_admin.fields_adv_link_text}</a>
                                        </span>
                                    }
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {  fpf_field_types[this.state.data.type]['is_available'] ?
                        <table className="fpf-table fpf-table-field-properies">
                            <tbody>
                            {  fpf_field_types[this.state.data.type]['has_required'] ?
                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label
                                            htmlFor={"field_required_" + this.state.id}>{fpf_admin.field_required_label}</label>
                                    </td>

                                    <td className="fpf-input">
                                        <input
                                            name="field_required"
                                            className="fpf-field-required"
                                            id={"field_required_" + this.state.id}
                                            type="checkbox"
                                            checked={this.state.data.required}
                                            onChange={this.onChangeRequired}
                                        />
                                    </td>
                                </tr>
                                : null
                            }

                            {  fpf_field_types[this.state.data.type]['has_placeholder'] ?
                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label
                                            htmlFor={"field_placeholder_" + this.state.id}>{fpf_admin.field_placeholder_label}</label>
                                    </td>

                                    <td className="fpf-input">
                                        <input type="text" className="fpf-field-placeholder"
                                               id={"field_placeholder_" + this.state.id} name="field_placeholder"
                                               value={this.state.data.placeholder} onChange={this.onChangePlaceholder}/>
                                    </td>
                                </tr>
                                : null
                            }

                            {  fpf_field_types[this.state.data.type]['has_value'] ?
                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label
                                            htmlFor={"field_value_" + this.state.id}>{fpf_admin.field_value_label}</label>
                                    </td>

                                    <td className="fpf-input">
                                        <input type="text" className="fpf-field-value"
                                               id={"field_value_" + this.state.id} name="field_value"
                                               value={this.state.data.value} onChange={this.onChangeValue}/>
                                    </td>
                                </tr>
                                : null
                            }

                            <tr className="fpf-field">
                                <td className="fpf-label">
                                    <label
                                        htmlFor={"field_css_class_" + this.state.id}>{fpf_admin.field_css_class_label}</label>
                                </td>

                                <td className="fpf-input">
                                    <input type="text" className="fpf-field-css-class"
                                           id={"field_css_class_" + this.state.id} name="field_css_class"
                                           value={this.state.data.css_class} onChange={this.onChangeCssClass}/>
                                </td>
                            </tr>

                            {  fpf_field_types[this.state.data.type]['has_price'] ?

                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label
                                            htmlFor={"field_price_type_" + this.state.id}>{fpf_admin.field_price_type_label}</label>
                                    </td>

                                    <td className="fpf-input">
                                        <select name="price_type" onChange={this.onChangePriceType}
                                                value={this.state.data.price_type}>
                                            {
                                                fpf_price_type_options.map(function (item) {
                                                    return <option key={item.value}
                                                                   value={item.value}>{item.label}</option>;
                                                })
                                            }
                                        </select>
                                    </td>
                                </tr>

                                :
                                null
                            }

                            {  fpf_field_types[this.state.data.type]['price_not_available'] ?

                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label
                                            htmlFor={"field_price_type_" + this.state.id}>{fpf_admin.field_price_type_label}</label>
                                    </td>

                                    <td className="fpf-input">
                                        {fpf_admin.price_adv} <a href={fpf_admin.price_adv_link}>{fpf_admin.price_adv_link_text}</a>
                                    </td>
                                </tr>

                                :
                                null
                            }

                            {  fpf_field_types[this.state.data.type]['has_price'] ?

                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label
                                            htmlFor={"field_price_" + this.state.id}>{fpf_admin.field_price_label}</label>
                                    </td>

                                    <td className="fpf-input">
                                        <input type="number" className="fpf-field-price"
                                               id={"field_price_" + this.state.id} name="field_price"
                                               value={this.state.data.price} onChange={this.onChangePrice}
                                               step="0.01"
                                        />
                                    </td>
                                </tr>

                                :
                                null
                            }

                            {  fpf_field_types[this.state.data.type]['price_not_available'] ?

                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label
                                            htmlFor={"field_price_" + this.state.id}>{fpf_admin.field_price_label}</label>
                                    </td>

                                    <td className="fpf-input">
                                        {fpf_admin.price_adv} <a href={fpf_admin.price_adv_link}>{fpf_admin.price_adv_link_text}</a>
                                    </td>
                                </tr>

                                :
                                null
                            }

                            {  fpf_field_types[this.state.data.type]['has_options'] ?
                                <tr className="fpf-field">
                                    <td className="fpf-label">
                                        <label>{fpf_admin.field_options_label}</label>
                                    </td>
                                    <td className="fpf-input">
                                        <table className="fpf-table fpf-options">
                                            <thead>
                                            <tr>
                                                <th className="fpf-row-handle"></th>
                                                <th>{fpf_admin.option_value_label}</th>
                                                <th>{fpf_admin.option_label_label}</th>
                                                <th className="fpf-row-price">{fpf_admin.option_price_type_label}</th>
                                                <th>{fpf_admin.option_price_label}</th>
                                                <th className="fpf-row-handle"></th>
                                            </tr>
                                            </thead>

                                            <SortableListOptions
                                                items={this.state.data.options}
                                                onRemove={this.onRemoveOption}
                                                useDragHandle={true}
                                                onSortEnd={this.onSortEndOptions}
                                                helperClass="fpf-option-drag"
                                            />

                                        </table>
                                        <button className="fpf-add-option button button-primary"
                                                onClick={this.handleAddOption}>
                                            {fpf_admin.add_option_label}
                                        </button>
                                    </td>
                                </tr>
                                :
                                null
                            }
                            </tbody>
                        </table>
                        : null
                    }
                </div>
            </div>
        );
    }
}


//var FPF_Fields = React.createClass({
class FPF_Fields extends React.Component {

    constructor( props ) {
        super( props );
        this.state = {
            fields: props.fields
        }
    }

    render() {
        return (
            <div>
                <SortableComponent items={this.state.fields} />
            </div>
        );
    }
}


//var FPF_Button_Add = React.createClass({
class FPF_Button_Add extends React.Component {

    constructor( props ) {
        super( props );
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick( e ) {
        e.preventDefault();
        var key = "fpf_"+Math.floor((Math.random() * 10000000) + 1);
        var data = {
            id: key,
            title: fpf_admin.new_field_title,
            type: 'text',
            required: false,
            placeholder: '',
            css_class: '',
            price: '',
            price_type: fpf_price_type_options[0].value,
            display: true,
        };
        this.props.addField(data);
    }

    render() {
        return (
            <button className="fpf-button-add button button-primary" onClick={this.handleClick}>
                {fpf_admin.add_field_label}
            </button>
        )
    }
}



//var FPF_Fields_Container = React.createClass({
class FPF_Fields_Container extends React.Component {

    constructor(props) {
        super(props);
        Fields = this;
        this.state = {
            fields: fpf_settings.fields,
            assign_to: fpf_settings.assign_to.value
        };
        this.addField = this.addField.bind(this);
        this.changeAssignTo = this.changeAssignTo.bind(this);
    }

    changeAssignTo( assign_to ) {
        this.setState(
            { assign_to: assign_to }
        );
    }

    addField( data ) {
        let fields = this.state.fields;
        fields.push( data );
        this.setState(
            { fields: fields }
        );
    }

    render() {
        console.log(fpf_settings.assign_to.value);
        return (
            <div className="fpf-fields-set">
                <div>
                    <FPF_Fields fields={this.state.fields} ref="fields"/>
                    <div className="fpf-footer">
                        < FPF_Button_Add addField={this.addField}/>
                    </div>
                </div>
            </div>
        );
    }
}

//var FPF_Settings_Assing_Group = React.createClass({
class FPF_Settings_Field extends React.Component {

    constructor(props) {
        super( props );
        console.log(props);
        this.state = {
            assign_to: props.assign_to,
            section: props.section,
            products: props.products,
            categories: props.categories,
        }
        if ( fpf_assign_to_values[this.state.assign_to.value]['is_available'] ) {
            document.getElementById('fpf_fields').style.display = 'block';
        }
        else {
            document.getElementById('fpf_fields').style.display = 'none';
        }
        this.assignToChange = this.assignToChange.bind(this);
        this.sectionChange = this.sectionChange.bind(this);
        this.productsChange = this.productsChange.bind(this);
        this.categoriesChange = this.categoriesChange.bind(this);

    }

    assignToChange(event) {
        var assign_to = this.state.assign_to;
        assign_to.value = event.target.value;
        this.setState( { assign_to: assign_to } );
        fpf_settings.assign_to = assign_to;
        if ( fpf_assign_to_values[this.state.assign_to.value]['is_available'] ) {
            document.getElementById('fpf_fields').style.display = 'block';
        }
        else {
            document.getElementById('fpf_fields').style.display = 'none';
        }
    }

    assignToChange2(val) {
        var assign_to = this.state.assign_to;
        assign_to.value = val.value;
        this.setState( { assign_to: assign_to } );
        //fpf_settings.assign_to = val.value;
    }

    sectionChange(event) {
        var section = this.state.section;
        section.value = event.target.value;
        this.setState( { section: section } );
        //fpf_settings.assign_to = val.value;
    }

    sectionChange2(val) {
        var section = this.state.section;
        section.value = val.value;
        this.setState( { section: section } );
        //fpf_settings.assign_to = val.value;
    }

    productsChange(val) {
        var products2 = this.state.products;
        products2.value = val;
        this.setState( { products: products2 } );
    }

    categoriesChange(val) {
        var categories2 = this.state.categories;
        categories2.value = val;
        this.setState( { categories: categories2 } );
    }

    render() {
        return (
            <table className="fpf-table">
                <tbody>
                    <tr className="fpf-field">
                        <td className="fpf-label">
                            <label htmlFor="">{fpf_admin.section_label}</label>
                        </td>

                        <td className="fpf-input">
                            <select name="section" onChange={this.sectionChange} value={this.state.section.value}>
                                {
                                    fpf_sections_options.map(function (item) {
                                        return <option key={item.value} value={item.value}>{item.label}</option>;
                                    })
                                }
                            </select>
                        </td>
                    </tr>
                    <tr className="fpf-field">
                        <td className="fpf-label">
                            <label htmlFor="">{fpf_admin.assign_to_label}</label>
                        </td>

                        <td className="fpf-input">
                            <select name="assing_to" onChange={this.assignToChange} value={this.state.assign_to.value}>
                                {
                                    fpf_assign_to_options.map(function (item) {
                                        return <option key={item.value}  disabled={item.disabled} value={item.value}>{item.label}</option>;
                                    })
                                }
                            </select>
                            {  fpf_assign_to_values[this.state.assign_to.value]['is_available'] ?
                                null
                                :
                                <span>
                                    <br/><br/>
                                    {fpf_admin.assign_to_adv} <a href={fpf_admin.assign_to_adv_link}>{fpf_admin.assign_to_adv_link_text}</a>
                                </span>
                            }
                        </td>
                    </tr>
                    { this.state.assign_to.value == 'product' && fpf_assign_to_values[this.state.assign_to.value]['is_available'] ?
                        <tr className="fpf-field">
                            <td className="fpf-label">
                                <label htmlFor="">{fpf_admin.products_label}</label>
                            </td>

                            <td className="fpf-input">
                                {  fpf_assign_to_values[this.state.assign_to.value]['is_available'] ?
                                    <Select.Async
                                        name="products"
                                        value={this.state.products.value}
                                        onChange={this.productsChange}
                                        loadOptions={getProductsOptions}
                                        placeholder={fpf_admin.select_placeholder}
                                        //simpleValue={true}
                                        searchPromptText={fpf_admin.select_type_to_search}
                                        multi={true}
                                        autoload={false}
                                        ref="products"
                                    />
                                    :
                                    <span>
                                        {fpf_admin.assign_to_adv} <a href={fpf_admin.assign_to_adv_link}>{fpf_admin.assign_to_adv_link_text}</a>
                                    </span>
                                }
                            </td>
                        </tr>
                        : null
                    }
                    { this.state.assign_to.value == 'category' && fpf_assign_to_values[this.state.assign_to.value]['is_available'] ?
                        <tr className="fpf-field">
                            <td className="fpf-label">
                                <label htmlFor="">{fpf_admin.categories_label}</label>
                            </td>

                            <td className="fpf-input">
                                {  fpf_assign_to_values[this.state.assign_to.value]['is_available'] ?
                                    <Select.Async
                                        name="categories"
                                        value={this.state.categories.value}
                                        onChange={this.categoriesChange}
                                        loadOptions={getCategoriesOptions}
                                        searchPromptText={fpf_admin.select_type_to_search}
                                        placeholder={fpf_admin.select_placeholder}
                                        //simpleValue={true}
                                        autoload={true}
                                        multi={true}
                                        ref="categories"
                                    />
                                    :
                                    <span>
                                        {fpf_admin.assign_to_adv} <a href={fpf_admin.assign_to_adv_link}>{fpf_admin.assign_to_adv_link_text}</a>
                                    </span>
                                }
                            </td>
                        </tr>
                        : null
                    }
                </tbody>
            </table>
        );
    }
}

class FPF_Settings_Container extends React.Component {

    constructor(props) {
        super(props);
        Settings = this;
        this.state = {
            settings: [ ]
        }
    }

    render() {
        return (
            <div className="fpf-fields-settings">
                <div className="fpf-inputs">
                    <FPF_Settings_Field
                        assign_to={fpf_settings.assign_to}
                        section={fpf_settings.section}
                        products={fpf_settings.products}
                        categories={fpf_settings.categories}
                        ref="settings_group"
                    />
                </div>
            </div>
        );
    }
}

class FPF_Actions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            message: ""
        };
        this.onClick = this.onClick.bind(this);
    }

    onClick(val) {
        fpf_settings.post_title.value = document.getElementById( 'title' ).value;
        var xmlhttp = new XMLHttpRequest();
        var _this = this;
        xmlhttp.onreadystatechange = function() {
            if ( xmlhttp.readyState === 4 ) {
                var response = JSON.parse( xmlhttp.responseText );
                if ( xmlhttp.status === 200 ) {
                    if ( response.code === 'ok' ) {
                        _this.setState({
                            type: 'success',
                            message: response.message
                        });
                    }
                    else {
                        _this.setState({
                            type: 'error',
                            message: response.message
                        });
                    }
                }
                else {
                    _this.setState( { type: 'error', message: fpf_admin.save_error + xmlhttp.status } );
                }
            }
        };
        xmlhttp.open( 'POST', fpf_admin.rest_url + 'flexible_product_fields/v1/fields/' + fpf_settings.post_id.value, true );
        xmlhttp.setRequestHeader( 'Content-type', 'application/json' );
        xmlhttp.setRequestHeader( 'X-WP-Nonce', fpf_admin.rest_nonce );
        xmlhttp.send( JSON.stringify( fpf_settings ) );
    }

    render() {
        return (
            <div id="publishing-action">
                <span className="spinner"></span>
                <input name="save" type="button" className="button button-primary button-large" id="publish" value="Update" onClick={this.onClick} />
                <div className="fpf-update-status">{this.state.message}</div>
            </div>
        );
    }
}

jQuery(document).ready(function () {
    if ( typeof fpf_settings != 'undefined' ) {
        jQuery('form').keydown(function(e) {
            if(e.which == 13) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        ReactDOM.render(
            < FPF_Settings_Container />,
            document.getElementById('fpf_settings_container')
        );
        ReactDOM.render(
            < FPF_Fields_Container />,
            document.getElementById('fpf_fields_container')
        );

        var fpf_saved = false;

        jQuery('#publish').click(function (e) {
            if (fpf_saved) {
                return;
            }
            document.getElementById('publish').disabled = true;
            document.getElementById('publish').className += ' disabled';
            e.preventDefault();

            fpf_settings.post_title.value = document.getElementById('title').value;
            var xmlhttp = new XMLHttpRequest();
            var _this = this;
            xmlhttp.onreadystatechange = function () {
                document.getElementById('publish').disabled = false;
                document.getElementById('publish').classList.remove("disabled");
                if (xmlhttp.readyState === 4) {
                    var response = JSON.parse(xmlhttp.responseText);
                    if (xmlhttp.status === 200) {
                        if (response.code === 'ok') {
                            fpf_saved = true;
                            document.getElementById('publish').click();
                        }
                        else {
                            alert( response.message );
                        }
                    }
                    else {
                        alert( fpf_admin.save_error + xmlhttp.status );
                    }
                }
            };
            xmlhttp.open('POST', fpf_admin.rest_url + 'flexible_product_fields/v1/fields/' + fpf_settings.post_id.value, true);
            xmlhttp.setRequestHeader('Content-type', 'application/json');
            xmlhttp.setRequestHeader('x-wp-nonce', fpf_admin.rest_nonce);
            xmlhttp.send(JSON.stringify(fpf_settings));
        })

        jQuery('#save-post').click(function (e) {
            if (fpf_saved) {
                return;
            }
            e.preventDefault();

            fpf_settings.post_title.value = document.getElementById('title').value;
            var xmlhttp = new XMLHttpRequest();
            var _this = this;
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4) {
                    var response = JSON.parse(xmlhttp.responseText);
                    if (xmlhttp.status === 200) {
                        if (response.code === 'ok') {
                            fpf_saved = true;
                            document.getElementById('save').click();
                        }
                        else {
                            alert( response.message );
                        }
                    }
                    else {
                        alert( fpf_admin.save_error + xmlhttp.status );
                    }
                }
            };
            xmlhttp.open('POST', fpf_admin.rest_url + 'flexible_product_fields/v1/fields/' + fpf_settings.post_id.value, true);
            xmlhttp.setRequestHeader('Content-type', 'application/json');
            xmlhttp.setRequestHeader('x-wp-nonce', fpf_admin.rest_nonce);
            xmlhttp.send(JSON.stringify(fpf_settings));
        })

    }


});
