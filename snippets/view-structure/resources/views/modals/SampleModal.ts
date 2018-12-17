import BaseModalHandler from "root/jquery-dependent/ModalHandler/BaseModalHandler";

class SampleModelHandler extends BaseModalHandler {
        constructor(container:any) {
        super(container);
    }

    initViewData(data:any|null|undefined, extraData?:any,callback?:any):void {
        
    }

    hookEvents(){
        super.hookEvents();
    }

    submit(form:any) {
    }


    fillViewFromData(extraData?: any): any {
    }
}

export default SampleModelHandler;