import Repository from './repository'

class HomeService{
    async getTitleHeader() {
        const endpoint = '/'
        const response = await Repository.get(endpoint)
        return response
    }
}
export default new HomeService()
