export default interface ModelIndexTableColumn {
    key?: string;
    label?: string;
    dataFormatCallback?: (value: string) => string;
    textAlign?: "right" | "center" | "left";
    bold?: boolean;
    allowTextWrap?: boolean;
    width?: string;
    slotName?: string;
    sortable?: boolean;
}
